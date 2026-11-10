<?php
require_once 'connection/db.php';

/**
 * Mengelola data ruangan: CRUD serta peminjaman dan pengembalian.
 * Semua query memakai prepared statement agar input tidak bisa menyisipkan SQL.
 */
class ruangan
{
    private $db;

    /**
     * Membuka koneksi database.
     * Kompleksitas: waktu O(1), memori O(1).
     */
    public function __construct()
    {
        $this->db = (new Database())->conn;
    }

    /**
     * Mengambil seluruh ruangan.
     *
     * @return array|null Daftar ruangan (array asosiatif), null jika query gagal.
     * Kompleksitas: waktu O(n), memori O(n), n = jumlah ruangan.
     */

    /**
     * Mengecek apakah ruangan sedang dipinjam.
     * Dipisah agar update dan delete memakai pengecekan yang sama.
     *
     * @param string $id_ruangan Id ruangan.
     * @return bool true jika sedang dipinjam.
     * Kompleksitas: waktu O(1) dari sisi PHP, memori O(1).
     */
    private function isDipinjam($id_ruangan)
    {
        $stmt = $this->db->prepare("SELECT status_ruangan FROM ruangan WHERE id_ruangan = ?");
        $stmt->execute([$id_ruangan]);

        return $stmt->fetchColumn() === "Tidak Tersedia";
    }

    public function getAllRuangan()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM ruangan");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Fetch failed: " . $e->getMessage();
        }
    }

    /**
     * Mengubah seluruh kolom ruangan berdasarkan id.
     * Status dan peminjam ikut diterima karena form update mengirim semuanya.
     *
     * @param string $id_ruangan Id ruangan yang diubah (kunci WHERE, tidak berubah).
     * @param string $nama_ruangan Nama baru.
     * @param int $kapasitas_ruangan Kapasitas baru.
     * @param string $id_gedung Id gedung baru.
     * @param string $status_ruangan Status baru ("Tersedia" / "Tidak Tersedia").
     * @param string|null $peminjam Nama peminjam, null jika kosong.
     * @return bool|null true jika berhasil, null jika terjadi error database.
     * Kompleksitas: waktu O(1) dari sisi PHP (di database bergantung indeks id), memori O(1).
     */
    public function updateRuangan($id_ruangan, $nama_ruangan, $kapasitas_ruangan, $id_gedung, $status_ruangan, $peminjam)
    {
        try {
            // Guard di server karena tombol di halaman bisa dilewati lewat request manual.
            if ($this->isDipinjam($id_ruangan)) {
                return false;
            }

            $stmt = $this->db->prepare("UPDATE ruangan SET status_ruangan = ?, peminjam = ?, nama_ruangan = ?, kapasitas_ruangan = ?, id_gedung = ? WHERE id_ruangan = ?");
            return $stmt->execute([$status_ruangan, $peminjam, $nama_ruangan, $kapasitas_ruangan, $id_gedung, $id_ruangan]);

        } catch (PDOException $e) {
            echo "Update failed: " . $e->getMessage();
        }
    }


    /**
     * Menambah ruangan baru.
     * Status awal selalu "Tersedia" dan peminjam null, karena ruangan baru belum dipinjam.
     *
     * @param string $id_ruangan Id unik ruangan.
     * @param string $nama_ruangan Nama ruangan.
     * @param int $kapasitas_ruangan Kapasitas ruangan.
     * @param string $id_gedung Id gedung tempat ruangan berada.
     * @return bool|null true jika berhasil, null jika terjadi error (misalnya id duplikat).
     * Kompleksitas: waktu O(1) dari sisi PHP, memori O(1).
     */
    public function insertRuangan($id_ruangan, $nama_ruangan, $kapasitas_ruangan, $id_gedung)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO ruangan(id_ruangan, nama_ruangan, kapasitas_ruangan, status_ruangan, id_gedung, peminjam) VALUES 
            (?, ?, ?, ?, ?, ?)");
            return $stmt->execute([$id_ruangan, $nama_ruangan, $kapasitas_ruangan, "Tersedia", $id_gedung, null]);

        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
        }
    }

    /**
     * Menghapus ruangan berdasarkan id.
     *
     * @param string $id_ruangan Id ruangan yang dihapus.
     * @return bool|null true jika berhasil, null jika terjadi error database.
     * Kompleksitas: waktu O(1) dari sisi PHP, memori O(1).
     */
    public function deleteRuangan($id_ruangan)
    {
        try {
            // Ruangan yang sedang dipinjam tidak boleh dihapus agar data peminjaman tidak hilang.
            if ($this->isDipinjam($id_ruangan)) {
                return false;
            }

            $stmt = $this->db->prepare("DELETE FROM ruangan WHERE id_ruangan = ?");
            return $stmt->execute([$id_ruangan]);

        } catch (PDOException $e) {
            echo "Delete failed: " . $e->getMessage();
        }
    }

    /**
     * Meminjamkan ruangan jika ada dan belum dipinjam.
     * Status dicek dulu agar ruangan yang sudah dipinjam tidak tertimpa peminjam lain.
     *
     * @param string $id_ruangan Id ruangan yang dipinjam.
     * @param string $peminjam Nama peminjam.
     * @return bool true jika berhasil; false jika ruangan tidak ada,
     *              sudah dipinjam, atau terjadi error database.
     * Kompleksitas: waktu O(1) dari sisi PHP (dua query), memori O(1).
     */
    public function pinjamRuangan($id_ruangan, $peminjam)
    {
        try {
            $cek = $this->db->prepare("SELECT status_ruangan FROM ruangan WHERE id_ruangan = ?");
            $cek->execute([$id_ruangan]);
            $ruanganData = $cek->fetch();

            // Guard: id tidak ditemukan.
            if ($ruanganData === false) {
                return false;
            }
            // Guard: ruangan sedang dipinjam orang lain.
            if ($ruanganData['status_ruangan'] == "Tidak Tersedia") {
                return false;
            }

            $stmt = $this->db->prepare("UPDATE ruangan SET status_ruangan = ?, peminjam = ? WHERE id_ruangan = ?");
            return $stmt->execute(["Tidak Tersedia", $peminjam, $id_ruangan]);
        } catch (PDOException $e) {
            echo "Pinjam failed" . $e->getMessage();
            return false;
        }
    }

    /**
     * Mengembalikan ruangan: status kembali "Tersedia" dan peminjam dikosongkan.
     *
     * @param string $id_ruangan Id ruangan yang dikembalikan.
     * @return bool|null true jika berhasil, null jika terjadi error database.
     * Kompleksitas: waktu O(1) dari sisi PHP, memori O(1).
     */
    public function kembalikanRuangan($id_ruangan)
    {
        try {
            $stmt = $this->db->prepare("UPDATE ruangan SET status_ruangan = ?, peminjam = ? WHERE id_ruangan = ?");
            return $stmt->execute(["Tersedia", null, $id_ruangan]);
        } catch (PDOException $e) {
            echo "kembalikan failed" . $e->getMessage();
        }

    }

}
?>