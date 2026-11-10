<?php
include 'view/FormRelasi.php';
// Diambil sekali agar tidak query ulang untuk setiap baris tabel.
$gedungPerPetugas = $relasi->getGedungPerPetugas();
?>

<h3>Tambah petugas</h3>
<form method="POST" action="?page=petugas">
    <label>ID:</label>
    <input type="text" name="id_petugas">
    <label>Nama:</label>
    <input type="text" name="nama_petugas">
    <label>Kontak:</label>
    <input type="text" name="kontak">
    <button type="submit" name="tambah">tambah</button>
</form>

<h3>Petugas List</h3>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>Kontak</th>
        <th>Gedung</th>
    </tr>
    <?php foreach ($petugas->getAllPetugas() as $m): ?>
        <tr>
            <td><?= $m['id_petugas'] ?></td>
            <td><?= $m['nama_petugas'] ?></td>
            <td><?= $m['kontak'] ?></td>
            <td>
                <?= implode(', ', $gedungPerPetugas[$m['id_petugas']] ?? []) ?>
            </td>
            <?php // Tombol aksi hanya terlihat di baris terpilih (lihat style.css). ?>
            <td class="row-actions">
                <?php // type="button" agar tidak mengirim form; yang dikirim adalah form di dialog. ?>
                <button type="button" data-update-dialog="dialog-update-petugas">Update</button>
                <?php // Form terpisah per baris agar hanya id baris ini yang terkirim saat hapus. ?>
                <form method="POST" action="?page=petugas" class="inline-form"
                    data-confirm="Yakin ingin menghapus petugas ini?">
                    <input type="hidden" name="id_petugas" value="<?= $m['id_petugas'] ?>">
                    <button type="submit" name="delete">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php // data-column = nomor sel pada baris tabel yang mengisi field ini (lihat fillDialog di script.js). ?>
<dialog id="dialog-update-petugas">
    <form method="POST" action="?page=petugas">
        <input type="hidden" name="id_petugas" data-column="0">
        <label>Nama:</label>
        <input type="text" name="nama_petugas" data-column="1">
        <label>Kontak:</label>
        <input type="text" name="kontak" data-column="2">
        <p></p>
        <button type="submit" name="update">Simpan</button>
        <button type="button" data-close-dialog>Batal</button>
    </form>
</dialog>