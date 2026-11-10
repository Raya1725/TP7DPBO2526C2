<?php
const KAPASITAS_MIN = 10;
const KAPASITAS_MAX = 50;
const KAPASITAS_STEP = 5;

// Dihitung sekali karena dipakai di form tambah dan dialog update.
$daftarKapasitas = range(KAPASITAS_MIN, KAPASITAS_MAX, KAPASITAS_STEP);
// Diambil sekali agar tidak query ulang untuk setiap dropdown gedung.
$daftarGedung = $gedung->getAllGedung();
?>
<h3>Tambah ruangan</h3>
<form method="POST" action="?page=ruangan">
    <label>ID:</label>
    <input type="text" id="id_ruangan" name="<?= "id_ruangan" ?>">
    <label>Nama Ruangan:</label>
    <input type="text" id="nama_ruangan" name="<?= "nama_ruangan" ?>">
    <p></p>
    <label>Gedung:</label>
    <select name="id_gedung">
        <?php foreach ($daftarGedung as $g): ?>
            <option value="<?= $g['id_gedung'] ?>"><?= $g['nama_gedung'] ?></option>
        <?php endforeach; ?>
    </select>
    <label>Kapasitas:</label>
    <select name="Kapasitas">
        <?php foreach ($daftarKapasitas as $kapasitas): ?>
            <option value="<?= $kapasitas ?>"><?= $kapasitas ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="tambah">tambah</button>
</form>

<h3>Ruangan List</h3>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Nama</th>
        <th>kapasitas</th>
        <th>status</th>
        <th>id_gedung</th>
        <th>peminjam</th>
    </tr>
    <?php foreach ($ruangan->getAllRuangan() as $b): ?>
        <tr>
            <td><?= $b['id_ruangan'] ?></td>
            <td><?= $b['nama_ruangan'] ?></td>
            <td><?= $b['kapasitas_ruangan'] ?></td>
            <td><?= $b['status_ruangan'] ?></td>
            <td><?= $b['id_gedung'] ?></td>
            <td><?= $b['peminjam'] ?></td>
            <td>
                <?php // Return hanya relevan untuk ruangan yang sedang dipinjam. ?>
                <?php if ($b['status_ruangan'] == "Tidak Tersedia"): ?>
                    <a href="?page=ruangan&return=<?= $b['id_ruangan'] ?>">Return</a>
                <?php endif; ?>
            </td>
            <?php // Tombol aksi ada di setiap baris, tetapi hanya terlihat di baris terpilih (lihat style.css). ?>
            <td class="row-actions">
                <?php // Tombol tidak dirender untuk ruangan yang sedang dipinjam. ?>
                <?php if ($b['status_ruangan'] != "Tidak Tersedia"): ?>
                    <button type="button" data-update-dialog="dialog-update-ruangan">Update</button>
                    <form method="POST" action="?page=ruangan" class="inline-form"
                        data-confirm="Yakin ingin menghapus ruangan ini?">
                        <input type="hidden" name="id_ruangan" value="<?= $b['id_ruangan'] ?>">
                        <button type="submit" name="delete">Delete</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<?php // data-column = nomor sel pada baris tabel yang mengisi field ini (lihat fillDialog di script.js). ?>
<dialog id="dialog-update-ruangan">
    <form method="POST" action="?page=ruangan">
        <?php // Status dan peminjam disembunyikan karena diubah lewat Borrow/Return, tapi harus ikut terkirim agar tidak tertimpa. ?>
        <input type="hidden" name="id_ruangan" data-column="0">
        <input type="hidden" name="status_ruangan" data-column="3">
        <input type="hidden" name="peminjam" data-column="5">
        <label>Nama Ruangan:</label>
        <input type="text" name="nama_ruangan" data-column="1">
        <p></p>
        <label>Gedung:</label>
        <select name="id_gedung" data-column="4">
            <?php foreach ($daftarGedung as $g): ?>
                <option value="<?= $g['id_gedung'] ?>"><?= $g['nama_gedung'] ?></option>
            <?php endforeach; ?>
        </select>
        <label>Kapasitas:</label>
        <select name="Kapasitas" data-column="2">
            <?php foreach ($daftarKapasitas as $kapasitas): ?>
                <option value="<?= $kapasitas ?>"><?= $kapasitas ?></option>
            <?php endforeach; ?>
        </select>
        <p></p>
        <button type="submit" name="update">Simpan</button>
        <button type="button" data-close-dialog>Batal</button>
    </form>
</dialog>

<h3>Pinjam ruangan</h3>
<form method="POST" action="?page=ruangan">
    <label>Ruangan:</label>
    <select name="id_ruangan">
        <?php foreach ($ruangan->getAllRuangan() as $b): ?>
            <option value="<?= $b['id_ruangan'] ?>"><?= $b['nama_ruangan'] ?></option>
        <?php endforeach; ?>
    </select>
    <label for="nama">Nama:</label>
    <input type="text" id="nama" name="<?= "nama" ?>">
    <button type="submit" name="borrow">Borrow</button>
</form>