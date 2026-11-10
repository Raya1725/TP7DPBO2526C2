<?php // Dipisah karena form yang sama dipakai di halaman Gedung dan Petugas. ?>
<h3>Tambah petugas ke gedung</h3>
<form method="POST" action="?page=<?= $page ?>">
    <label>Petugas:</label>
    <select name="id_petugas">
        <?php foreach ($petugas->getAllPetugas() as $p): ?>
            <option value="<?= $p['id_petugas'] ?>"><?= $p['nama_petugas'] ?></option>
        <?php endforeach; ?>
    </select>
    <label>Gedung:</label>
    <select name="id_gedung">
        <?php foreach ($gedung->getAllGedung() as $g): ?>
            <option value="<?= $g['id_gedung'] ?>"><?= $g['nama_gedung'] ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" name="tambah_relasi">tambah</button>
</form>


<h3>Relasi List</h3>
<table border="1">
    <tr>
        <th>ID Petugas</th>
        <th>ID Gedung</th>
    </tr>
    <?php foreach ($relasi->getAllRelasi() as $r): ?>
        <tr>
            <td>
                <?= $r['id_petugas'] ?>
            </td>
            <td>
                <?= $r['id_gedung'] ?>
            </td>
            <td class="row-actions">
                <button type="button" data-update-dialog="dialog-update-relasi">Update</button>
                <form method="POST" action="?page=<?= $page ?>" class="inline-form"
                    data-confirm="Yakin ingin menghapus relasi ini?">
                    <input type="hidden" name="id_petugas" value="<?= $r['id_petugas'] ?>">
                    <input type="hidden" name="id_gedung" value="<?= $r['id_gedung'] ?>">
                    <button type="submit" name="delete_relasi">Delete</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
</table>

<dialog id="dialog-update-relasi">
    <form method="POST" action="?page=<?= $page ?>">
        <input type="hidden" name="id_petugas_lama" data-column="0">
        <input type="hidden" name="id_gedung_lama" data-column="1">
        <label>Petugas:</label>
        <select name="id_petugas" data-column="0">
            <?php foreach ($petugas->getAllPetugas() as $p): ?>
                <option value="<?= $p['id_petugas'] ?>">
                    <?= $p['nama_petugas'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label>Gedung:</label>
        <select name="id_gedung" data-column="1">
            <?php foreach ($gedung->getAllGedung() as $g): ?>
                <option value="<?= $g['id_gedung'] ?>">
                    <?= $g['nama_gedung'] ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p></p>
        <button type="submit" name="update_relasi">Simpan</button>
        <button type="button" data-close-dialog>Batal</button>
    </form>
</dialog>