<?php
require_once 'class/ruangan.php';
require_once 'class/petugas.php';
require_once 'class/gedung.php';
require_once 'class/petugas_dan_gedung.php';

$ruangan = new ruangan();
$gedung = new gedung();
$petugas = new petugas();
$relasi = new petugas_dan_gedung();
$page = $_GET['page'];

// Semua aksi form ditangani di sini sebelum HTML dirender, supaya tabel
// yang ditampilkan sudah berisi data terbaru. Aksi dibedakan oleh nama
// tombol submit dan halaman aktif.
if (isset($_POST['borrow']) && $page == 'ruangan') {
    $ruangan->pinjamRuangan($_POST['id_ruangan'], $_POST['nama']);
} else if (isset($_GET['return']) && $page == 'ruangan') {
    $ruangan->kembalikanRuangan($_GET['return']);
} else if (isset($_POST['tambah']) && $page == 'ruangan') {
    $ruangan->insertRuangan($_POST['id_ruangan'], $_POST['nama_ruangan'], $_POST['Kapasitas'], $_POST['id_gedung']);
} else if (isset($_POST['update']) && $page == 'ruangan') {
    // Peminjam kosong disimpan sebagai NULL agar sama dengan data hasil insert.
    $peminjam = $_POST['peminjam'] === '' ? null : $_POST['peminjam'];
    $ruangan->updateRuangan($_POST['id_ruangan'], $_POST['nama_ruangan'], $_POST['Kapasitas'], $_POST['id_gedung'], $_POST['status_ruangan'], $peminjam);
} else if (isset($_POST['delete']) && $page == 'ruangan') {
    $ruangan->deleteRuangan($_POST['id_ruangan']);
} else if (isset($_POST['tambah']) && $page == 'petugas') {
    $petugas->insertPetugas($_POST['id_petugas'], $_POST['nama_petugas'], $_POST['kontak']);
} else if (isset($_POST['update']) && $page == 'petugas') {
    $petugas->updatePetugas($_POST['id_petugas'], $_POST['nama_petugas'], $_POST['kontak']);
} else if (isset($_POST['delete']) && $page == 'petugas') {
    $petugas->deletePetugas($_POST['id_petugas']);
} else if (isset($_POST['tambah']) && $page == 'gedung') {
    $gedung->insertGedung($_POST['id_gedung'], $_POST['nama_gedung'], $_POST['jumlah_lantai']);
} else if (isset($_POST['update']) && $page == 'gedung') {
    $gedung->updateGedung($_POST['id_gedung'], $_POST['nama_gedung'], $_POST['jumlah_lantai']);
} else if (isset($_POST['delete']) && $page == 'gedung') {
    $gedung->deleteGedung($_POST['id_gedung']);
} else if (isset($_POST['tambah_relasi']) && ($page == 'gedung' || $page == 'petugas')) {
    $relasi->insertRelasi($_POST['id_petugas'], $_POST['id_gedung']);
} else if (isset($_POST['update_relasi']) && ($page == 'gedung' || $page == 'petugas')) {
    $relasi->updateRelasi($_POST['id_petugas_lama'], $_POST['id_gedung_lama'], $_POST['id_petugas'], $_POST['id_gedung']);
} else if (isset($_POST['delete_relasi']) && ($page == 'gedung' || $page == 'petugas')) {
    $relasi->deleteRelasi($_POST['id_petugas'], $_POST['id_gedung']);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Peminjaman ruangan</title>
    <link rel="stylesheet" href="style.css">
    <!-- Dimuat di head tanpa defer aman, karena script.js hanya memasang
         listener di document dan tidak menyentuh elemen secara langsung. -->
    <script src="script.js"></script>
</head>

<body>
    <?php include 'view/header.php'; ?>
    <main>
        <h2>Welcome to Room System</h2>
        <nav>
            <a href="?page=ruangan">Ruangan</a> |
            <a href="?page=petugas">Petugas</a> |
            <a href="?page=gedung">Gedung</a>
        </nav>

        <?php
        // Memuat view sesuai halaman yang dipilih lewat URL.
        if (isset($_GET['page'])) {
            if ($page == 'ruangan')
                include 'view/Ruangan.php';
            elseif ($page == 'petugas')
                include 'view/Petugas.php';
            elseif ($page == 'gedung')
                include 'view/Gedung.php';
        }
        ?>
    </main>
    <?php include 'view/footer.php'; ?>
</body>

</html>