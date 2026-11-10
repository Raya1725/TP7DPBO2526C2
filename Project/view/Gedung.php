<?php
include 'view/FormRelasi.php';
// Diambil sekali agar tidak query ulang untuk setiap baris tabel.
$petugasPerGedung = $relasi->getPetugasPerGedung();
?>

<h3>Tambah gedung</h3>
<form method="POST" action="?page=gedung">
    <label>ID:</label>
    <input type="text" name="id_gedung">
    <label>Nama:</label>
    <input type="text" name="nama_gedung">
    <label>Jumlah lantai:</label>
    <input type="number" name="jumlah_lantai">
    <button type="submit" name="tambah">tambah</button>
</form>

<h3>Gedung List</h3>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>jumlah lantai</th>
        <th>petugas</th>
    </tr>
    <?php foreach ($gedung->getAllGedung() as $l): ?>
        <tr>
            <td><?= $l['id_gedung'] ?></td>
            <td><?= $l['nama_gedung'] ?></td>
            <td><?= $l['jumlah_lantai'] ?></td>
            <td>
                <?= implode(', ', $petugasPerGedung[$l['id_gedung']] ?? []) ?>
            </td>
            <?php // Tombol aksi hanya terlihat di baris terpilih (lihat style.css). ?>
            <td class="row-actions">
                <?php // type="button" agar tidak mengirim form; yang dikirim adalah form di dialog. ?>
                <button type="button" data-update-dialog="dialog-update-gedung">Update</button>
                <?php // Form terpisah per baris agar hanya id baris ini yang terkirim saat hapus. ?>
                <form method="POST" action="?page=gedung" class="inline-form"
                    data-confirm="Yakin ingin menghapus gedung ini?">
                    <input type="hidden" name="id_gedung" value="<?= $l['id_gedung'] ?>">
                    <button type="submit" name="delete">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php // data-column = nomor sel pada baris tabel yang mengisi field ini (lihat fillDialog di script.js). ?>
<dialog id="dialog-update-gedung">
    <form method="POST" action="?page=gedung">
        <input type="hidden" name="id_gedung" data-column="0">
        <label>Nama:</label>
        <input type="text" name="nama_gedung" data-column="1">
        <label>Jumlah lantai:</label>
        <input type="number" name="jumlah_lantai" data-column="2">
        <p></p>
        <button type="submit" name="update">Simpan</button>
        <button type="button" data-close-dialog>Batal</button>
    </form>
</dialog>