<?php
require_once 'connection/db.php';

/**
 * Mengelola relasi petugas dan gedung (banyak ke banyak).
 * Tabel relasi dipakai karena satu petugas bisa bekerja di beberapa gedung
 * dan satu gedung bisa punya beberapa petugas.
 */
class petugas_dan_gedung
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
     * Menambah relasi petugas ke gedung.
     *
     * @param string $id_petugas Id petugas.
     * @param string $id_gedung Id gedung.
     * @return bool|null true jika berhasil, null jika error (misalnya relasi sudah ada).
     * Kompleksitas: waktu O(1) dari sisi PHP, memori O(1).
     */
    public function insertRelasi($id_petugas, $id_gedung)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO petugas_dan_gedung(id_petugas, id_gedung) VALUES (?, ?)");
            return $stmt->execute([$id_petugas, $id_gedung]);
        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
        }
    }

    /**
     * Mengambil nama petugas per gedung dalam satu query.
     * FETCH_GROUP dipakai agar pengelompokan dikerjakan PDO, bukan perulangan manual,
     * dan tabel tidak perlu query ulang untuk setiap baris.
     *
     * @return array|null [id_gedung => [nama_petugas, ...]], null jika query gagal.
     * Kompleksitas: waktu O(r), memori O(r), r = jumlah relasi.
     */
    public function getPetugasPerGedung()
    {
        try {
            $stmt = $this->db->query("SELECT pg.id_gedung, p.nama_petugas
                FROM petugas_dan_gedung pg
                JOIN petugas p ON p.id_petugas = pg.id_petugas");
            return $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            echo "Fetch failed: " . $e->getMessage();
        }
    }

    /**
     * Mengambil nama gedung per petugas dalam satu query.
     *
     * @return array|null [id_petugas => [nama_gedung, ...]], null jika query gagal.
     * Kompleksitas: waktu O(r), memori O(r), r = jumlah relasi.
     */
    public function getGedungPerPetugas()
    {
        try {
            $stmt = $this->db->query("SELECT pg.id_petugas, g.nama_gedung
                FROM petugas_dan_gedung pg
                JOIN gedung g ON g.id_gedung = pg.id_gedung");
            return $stmt->fetchAll(PDO::FETCH_GROUP | PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            echo "Fetch failed: " . $e->getMessage();
        }
    }

    /**
     * Mengambil seluruh pasangan relasi.
     *
     * @return array|null Daftar relasi (array asosiatif), null jika query gagal.
     * Kompleksitas: waktu O(r), memori O(r), r = jumlah relasi.
     */
    public function getAllRelasi()
    {
        try {
            $stmt = $this->db->query("SELECT id_petugas, id_gedung FROM petugas_dan_gedung");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Fetch failed: " . $e->getMessage();
        }
    }

    /**
     * Mengganti pasangan relasi lama dengan pasangan baru.
     * Pasangan lama ikut diterima karena kunci tabel ini gabungan dua kolom.
     *
     * @param string $id_petugas_lama Id petugas pada relasi lama.
     * @param string $id_gedung_lama Id gedung pada relasi lama.
     * @param string $id_petugas Id petugas baru.
     * @param string $id_gedung Id gedung baru.
     * @return bool|null true jika berhasil, null jika error (misalnya pasangan baru sudah ada).
     * Kompleksitas: waktu O(1) dari sisi PHP, memori O(1).
     */
    public function updateRelasi($id_petugas_lama, $id_gedung_lama, $id_petugas, $id_gedung)
    {
        try {
            $stmt = $this->db->prepare("UPDATE petugas_dan_gedung SET id_petugas = ?, id_gedung = ? WHERE id_petugas = ? AND id_gedung = ?");
            return $stmt->execute([$id_petugas, $id_gedung, $id_petugas_lama, $id_gedung_lama]);
        } catch (PDOException $e) {
            echo "Update failed: " . $e->getMessage();
        }
    }

    /**
     * Menghapus satu relasi petugas dan gedung.
     *
     * @param string $id_petugas Id petugas.
     * @param string $id_gedung Id gedung.
     * @return bool|null true jika berhasil, null jika terjadi error database.
     * Kompleksitas: waktu O(1) dari sisi PHP, memori O(1).
     */
    public function deleteRelasi($id_petugas, $id_gedung)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM petugas_dan_gedung WHERE id_petugas = ? AND id_gedung = ?");
            return $stmt->execute([$id_petugas, $id_gedung]);
        } catch (PDOException $e) {
            echo "Delete failed: " . $e->getMessage();
        }
    }

}