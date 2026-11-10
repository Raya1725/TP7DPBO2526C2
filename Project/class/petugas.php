<?php
require_once 'connection/db.php';

/**
 * Mengelola data petugas (CRUD).
 * Semua query memakai prepared statement agar input tidak bisa menyisipkan SQL.
 */
class petugas
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
     * Mengambil seluruh petugas.
     *
     * @return array|null Daftar petugas (array asosiatif), null jika query gagal.
     * Kompleksitas: waktu O(n), memori O(n), n = jumlah petugas.
     */
    public function getAllPetugas()
    {
        try {
            $stmt = $this->db->query("SELECT * FROM petugas");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Fetch failed: " . $e->getMessage();
        }
    }

    /**
     * Menambah petugas baru.
     *
     * @param string $id_petugas Id unik petugas.
     * @param string $nama_petugas Nama petugas.
     * @param string $kontak Kontak petugas.
     * @return bool|null true jika berhasil, null jika terjadi error (misalnya id duplikat).
     * Kompleksitas: waktu O(1) dari sisi PHP, memori O(1).
     */
    public function insertPetugas($id_petugas, $nama_petugas, $kontak)
    {
        try {
            $stmt = $this->db->prepare("INSERT INTO petugas(id_petugas, nama_petugas, kontak) VALUES (?, ?, ?)");
            return $stmt->execute([$id_petugas, $nama_petugas, $kontak]);
        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
        }
    }

    /**
     * Mengubah nama dan kontak petugas berdasarkan id.
     *
     * @param string $id_petugas Id petugas yang diubah (kunci WHERE, tidak berubah).
     * @param string $nama_petugas Nama baru.
     * @param string $kontak Kontak baru.
     * @return bool|null true jika berhasil, null jika terjadi error database.
     * Kompleksitas: waktu O(1) dari sisi PHP, memori O(1).
     */
    public function updatePetugas($id_petugas, $nama_petugas, $kontak)
    {
        try {
            $stmt = $this->db->prepare("UPDATE petugas SET nama_petugas = ?, kontak = ? WHERE id_petugas = ?");
            return $stmt->execute([$nama_petugas, $kontak, $id_petugas]);
        } catch (PDOException $e) {
            echo "Update failed: " . $e->getMessage();
        }
    }

    /**
     * Menghapus petugas berdasarkan id.
     *
     * @param string $id_petugas Id petugas yang dihapus.
     * @return bool|null true jika berhasil, null jika terjadi error database.
     * Kompleksitas: waktu O(1) dari sisi PHP, memori O(1).
     */
    public function deletePetugas($id_petugas)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM petugas WHERE id_petugas = ?");
            return $stmt->execute([$id_petugas]);
        } catch (PDOException $e) {
            echo "Insert failed: " . $e->getMessage();
        }
    }


}

?>