<?php
require_once 'connection/db.php';

/**
 * Mengelola data gedung (CRUD).
 * Semua query memakai prepared statement agar input tidak bisa menyisipkan SQL.
 */
class gedung
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
     * Mengambil seluruh gedung.
     *
     * @return array|null Daftar gedung (array asosiatif), null jika query gagal.
     * Kompleksitas: waktu O(n), memori O(n), n = jumlah gedung.
     */
    public function getAllGedung()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM gedung");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo "Fetch failed: " . $e->getMessage();
        }
    }

    /**
     * Menambah gedung baru.
     *
     * @param string $id_gedung Id unik gedung.
     * @param string $nama_gedung Nama gedung.
     * @param int $jumlah_lantai Jumlah lantai gedung.
     * @return bool|null true jika berhasil, null jika terjadi error (misalnya id duplikat).
     * Kompleksitas: waktu O(1) dari sisi PHP, memori O(1).
     */
    public function insertGedung($id_gedung, $nama_gedung, $jumlah_lantai)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO gedung(id_gedung, nama_gedung, jumlah_lantai) VALUES 
            (?, ?, ?)");
            return $stmt->execute([$id_gedung, $nama_gedung, $jumlah_lantai]);

        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
        }
    }

    /**
     * Mengubah nama dan jumlah lantai gedung berdasarkan id.
     *
     * @param string $id_gedung Id gedung yang diubah (kunci WHERE, tidak berubah).
     * @param string $nama_gedung Nama baru.
     * @param int $jumlah_lantai Jumlah lantai baru.
     * @return bool|null true jika berhasil, null jika terjadi error database.
     * Kompleksitas: waktu O(1) dari sisi PHP, memori O(1).
     */
    public function updateGedung($id_gedung, $nama_gedung, $jumlah_lantai)
    {
        try {
            $stmt = $this->db->prepare("UPDATE gedung SET nama_gedung = ?, jumlah_lantai = ? WHERE id_gedung = ?");
            return $stmt->execute([$nama_gedung, $jumlah_lantai, $id_gedung]);

        } catch (PDOException $e) {
            echo "Update failed: " . $e->getMessage();
        }
    }

    /**
     * Menghapus gedung berdasarkan id.
     * Bisa ditolak database jika gedung masih dipakai ruangan (foreign key).
     *
     * @param string $id_gedung Id gedung yang dihapus.
     * @return bool|null true jika berhasil, null jika terjadi error database.
     * Kompleksitas: waktu O(1) dari sisi PHP, memori O(1).
     */
    public function deleteGedung($id_gedung)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM gedung WHERE id_gedung = ?");
            return $stmt->execute([$id_gedung]);

        } catch (PDOException $e) {
            echo "Delete failed: " . $e->getMessage();
        }
    }
}