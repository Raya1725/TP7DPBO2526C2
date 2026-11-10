# TP7DPBO2526C2
TUGAS PRAKTIKUM 7 DPBO OOP DALAM WEB (PHP & MySQL) Renaldy Heryana (2509867) Ilmu Komputer C2 Universitas Pendidikan Indonesia

# janji
Saya Renaldy Heryana dengan NIM 2509867 mengerjakan TP7 dalam mata kuliah Desain dan Pemrograman Berorientasi Objek untuk keberkahanNya maka saya tidak melakukan kecurangan seperti yang telah dispesifikasikan. Aamiin.

# TEMA WEBSITE

Website ini bernama **Room Management System**, yaitu sistem peminjaman ruangan di lingkungan kampus. Website digunakan untuk mengelola tiga data utama, yaitu ruangan, petugas, dan gedung. Ruangan berada di dalam sebuah gedung dan dapat dipinjam lalu dikembalikan. Satu gedung dapat dijaga oleh banyak petugas, dan satu petugas dapat bekerja di banyak gedung, sehingga hubungan keduanya dicatat pada tabel relasi. Semua data dapat ditambah, dilihat, diubah, dan dihapus (CRUD) langsung dari browser.

# DATABASE

Nama database: `db_pinjam_ruangan` (MySQL/MariaDB). Akses database memakai PDO.

## TABEL

### Tabel `gedung`
Menyimpan data gedung.

- id_gedung, adalah primary key berupa kode gedung (contoh: FPEB001)
- nama_gedung, adalah nama gedung (contoh: Gedung FPEB B)
- jumlah_lantai, adalah jumlah lantai gedung

### Tabel `petugas`
Menyimpan data petugas.

- id_petugas, adalah primary key berupa kode petugas (contoh: P001)
- nama_petugas, adalah nama petugas
- kontak, adalah kontak petugas

### Tabel `ruangan`
Menyimpan data ruangan beserta status peminjamannya.

- id_ruangan, adalah primary key berupa kode ruangan
- nama_ruangan, adalah nama ruangan
- kapasitas_ruangan, adalah kapasitas ruangan (pilihan 10 sampai 50, kelipatan 5)
- status_ruangan, adalah status ruangan, yaitu "Tersedia" atau "Tidak Tersedia"
- id_gedung, adalah gedung tempat ruangan berada
- peminjam, adalah nama peminjam, bernilai NULL jika ruangan tidak sedang dipinjam

### Tabel `petugas_dan_gedung`
Tabel relasi many-to-many antara petugas dan gedung. Kuncinya adalah gabungan dua kolom.

- id_petugas, adalah foreign key ke `petugas.id_petugas`
- id_gedung, adalah foreign key ke `gedung.id_gedung` (constraint `petugas_dan_gedung_ibfk_2`)

## RELASI ANTAR TABEL

- `gedung` dan `petugas` berhubungan many-to-many lewat `petugas_dan_gedung`
- `gedung` dan `ruangan` berhubungan one-to-many, satu gedung memiliki banyak ruangan
- Gedung yang masih memiliki relasi petugas tidak dapat dihapus karena terikat foreign key. Relasinya harus dihapus lebih dulu

File SQL hasil export database ada di folder `daatabase`.

# STRUKTUR REPO

```
TP7DPBO2526C2
├── Dokumentasi
│   └── Dokum Tp7.webm
├── Project
│   ├── class
│   │   ├── gedung.php
│   │   ├── petugas.php
│   │   ├── petugas_dan_gedung.php
│   │   └── ruangan.php
│   ├── connection
│   │   └── db.php
│   ├── daatabase
│   ├── view
│   │   ├── FormRelasi.php
│   │   ├── Gedung.php
│   │   ├── Petugas.php
│   │   ├── Ruangan.php
│   │   ├── footer.php
│   │   └── header.php
│   ├── index.php
│   ├── script.js
│   └── style.css
└── README.md
```

# ALUR PROGRAM

1. Program dimulai dari `index.php`. File ini memuat keempat class, membuat objek `ruangan`, `gedung`, `petugas`, dan `petugas_dan_gedung`, lalu membaca parameter `page` pada URL (`?page=ruangan`, `?page=petugas`, atau `?page=gedung`).
2. Semua aksi form diproses di bagian atas `index.php`, sebelum HTML dirender, agar tabel yang tampil sudah berisi data terbaru. Aksi dibedakan oleh nama tombol submit (`tambah`, `update`, `delete`, `borrow`, `tambah_relasi`, `update_relasi`, `delete_relasi`) dan halaman yang sedang aktif.
3. Setelah itu `index.php` merender layout: `header.php` (judul "Room Management System"), menu Ruangan | Petugas | Gedung, isi halaman, dan `footer.php`. Isi halaman dimuat dari folder `view` sesuai nilai `page`.
4. **Create**: user mengisi form tambah lalu menekan tombol "tambah". Data dikirim lewat `POST`, diteruskan `index.php` ke method `insert` class terkait, lalu halaman dirender ulang dengan data baru. Ruangan baru selalu berstatus "Tersedia" dan tanpa peminjam.
5. **Read**: setiap view memanggil method `getAll` untuk menampilkan data dalam tabel. Halaman Gedung menampilkan kolom petugas, dan halaman Petugas menampilkan kolom gedung. Kedua kolom itu diambil dengan satu query `JOIN` yang dikelompokkan PDO (`FETCH_GROUP`), sehingga tidak ada query per baris.
6. **Update**: user mengklik sebuah baris tabel, baris menjadi berwarna kuning dan tombol Update serta Delete muncul. Menekan Update membuka dialog yang field-nya terisi otomatis dari sel baris tersebut oleh `script.js`. Menekan Simpan mengirim data ke method `update`.
7. **Delete**: tombol Delete muncul pada baris terpilih. Sebelum dikirim, browser meminta konfirmasi lewat `confirm`. Setelah dikonfirmasi, data dihapus lewat method `delete`.
8. **Pinjam dan kembalikan ruangan**: pada halaman Ruangan, form "Pinjam ruangan" memilih ruangan yang masih tersedia dan mengisi nama peminjam. Tombol Borrow mengubah status menjadi "Tidak Tersedia" dan menyimpan peminjam. Link Return pada ruangan yang sedang dipinjam mengembalikan status menjadi "Tersedia" dan mengosongkan peminjam. Ruangan yang sedang dipinjam tidak dapat diubah atau dihapus.
9. **Relasi petugas dan gedung**: halaman Gedung dan Petugas memakai `FormRelasi.php` yang sama. File ini berisi form "Tambah petugas ke gedung", tabel "Relasi List", dan dialog update relasi. Tambah, ubah, dan hapus relasi dikerjakan class `petugas_dan_gedung`.
10. Program berjalan selama server PHP aktif.

# CARA MENJALANKAN

1. Buat database `db_pinjam_ruangan` lalu import file `.sql` dari folder `daatabase`:
   ```bash
   mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS db_pinjam_ruangan"
   mysql -u root -p db_pinjam_ruangan < Project/daatabase/<nama file>.sql
   ```
2. Sesuaikan host, user, dan password database pada `Project/connection/db.php`.
3. Jalankan server PHP dari folder `Project`:
   ```bash
   php -S localhost:8000
   ```
4. Buka `http://localhost:8000/?page=ruangan` di browser.

# ERROR HANDLING

1. Semua method class dibungkus `try-catch` pada `PDOException`. Jika query gagal (misalnya ID duplikat), pesan seperti "Insert failed: ..." ditampilkan dan program tidak berhenti.
2. Semua query memakai prepared statement agar input user tidak bisa menyisipkan SQL.
3. Gedung yang masih terikat relasi petugas atau ruangan ditolak database lewat foreign key. Pesan penolakannya ditampilkan oleh `catch` pada `deleteGedung`.
4. Ruangan yang sedang dipinjam ditolak oleh `updateRuangan` dan `deleteRuangan` lewat pengecekan `isDipinjam`. Tombol Update dan Delete untuk ruangan tersebut juga tidak dirender. Pengecekan di server tetap ada karena tombol bisa dilewati lewat request manual.
5. `pinjamRuangan` mengembalikan `false` jika ID ruangan tidak ditemukan atau ruangan sudah dipinjam, sehingga peminjam lain tidak menimpa data peminjam sebelumnya.
6. Peminjam yang kosong pada form update disimpan sebagai `NULL`, sama dengan data hasil insert.
7. Penghapusan data memakai konfirmasi `confirm` di browser agar tidak terhapus tanpa sengaja.
8. Gedung tanpa petugas dan petugas tanpa gedung tetap tampil dengan kolom kosong karena memakai operator `??`.

# PENJELASAN ATRIBUT DAN METHOD

Setiap class pada folder `class` mengelola satu tabel. Semua class memiliki atribut dan constructor yang sama, lalu method CRUD sesuai tabelnya.

## ATRIBUT UMUM

- db, adalah atribut private berisi koneksi PDO yang didapat dari class `Database` pada `connection/db.php`

## METHOD UMUM

- constructor, adalah method untuk membuka koneksi database dan menyimpannya ke atribut `db`

## CLASS gedung

Mengelola tabel `gedung`.

- getAllGedung, adalah method untuk mengambil seluruh gedung
- insertGedung, adalah method untuk menambah gedung baru (id_gedung, nama_gedung, jumlah_lantai)
- updateGedung, adalah method untuk mengubah nama dan jumlah lantai gedung berdasarkan id
- deleteGedung, adalah method untuk menghapus gedung berdasarkan id

## CLASS petugas

Mengelola tabel `petugas`.

- getAllPetugas, adalah method untuk mengambil seluruh petugas
- insertPetugas, adalah method untuk menambah petugas baru (id_petugas, nama_petugas, kontak)
- updatePetugas, adalah method untuk mengubah nama dan kontak petugas berdasarkan id
- deletePetugas, adalah method untuk menghapus petugas berdasarkan id

## CLASS ruangan

Mengelola tabel `ruangan`, termasuk peminjaman dan pengembalian.

- isDipinjam, adalah method private untuk mengecek apakah ruangan sedang dipinjam. Dipisah agar update dan delete memakai pengecekan yang sama
- getAllRuangan, adalah method untuk mengambil seluruh ruangan
- insertRuangan, adalah method untuk menambah ruangan baru dengan status awal "Tersedia" dan peminjam NULL
- updateRuangan, adalah method untuk mengubah nama, kapasitas, gedung, status, dan peminjam ruangan. Ditolak jika ruangan sedang dipinjam
- deleteRuangan, adalah method untuk menghapus ruangan berdasarkan id. Ditolak jika ruangan sedang dipinjam
- pinjamRuangan, adalah method untuk meminjamkan ruangan. Status dicek dulu, lalu diubah menjadi "Tidak Tersedia" dan nama peminjam disimpan
- kembalikanRuangan, adalah method untuk mengembalikan status menjadi "Tersedia" dan mengosongkan peminjam

## CLASS petugas_dan_gedung

Mengelola tabel relasi `petugas_dan_gedung`.

- insertRelasi, adalah method untuk menambah relasi petugas ke gedung
- getPetugasPerGedung, adalah method untuk mengambil nama petugas yang dikelompokkan per gedung dalam satu query
- getGedungPerPetugas, adalah method untuk mengambil nama gedung yang dikelompokkan per petugas dalam satu query
- getAllRelasi, adalah method untuk mengambil seluruh pasangan relasi
- updateRelasi, adalah method untuk mengganti pasangan relasi lama dengan pasangan baru. Pasangan lama ikut dikirim karena kunci tabel ini gabungan dua kolom
- deleteRelasi, adalah method untuk menghapus satu relasi berdasarkan pasangan petugas dan gedung

## FILE VIEW

- header.php dan footer.php, adalah tampilan judul dan footer yang sama di semua halaman
- Ruangan.php, adalah halaman ruangan berisi form tambah, tabel, dialog update, dan form pinjam
- Petugas.php, adalah halaman petugas berisi form tambah, tabel, dan dialog update
- Gedung.php, adalah halaman gedung berisi form tambah, tabel, dan dialog update
- FormRelasi.php, adalah form, tabel, dan dialog relasi petugas dan gedung yang dipakai bersama oleh halaman Gedung dan Petugas

## FILE script.js

- isDataRow, adalah fungsi untuk memastikan baris yang diklik adalah baris data, bukan baris judul
- selectRow, adalah fungsi untuk menandai satu baris sebagai terpilih dan melepas tanda baris sebelumnya
- fillDialog, adalah fungsi untuk mengisi field dialog dari sel baris memakai atribut `data-column`
- openUpdateDialog, adalah fungsi untuk membuka dialog update yang terisi data baris tombol tersebut
- listener `click` pada document, adalah event delegation untuk tombol Update, tombol Batal, dan pemilihan baris
- listener `submit` pada document, adalah konfirmasi sebelum form dengan atribut `data-confirm` dikirim

# DOKUMENTASI

Dokumentasi berupa screen record yang mencoba CRUD pada semua tabel (ruangan, petugas, gedung, dan relasi petugas dan gedung), termasuk peminjaman dan pengembalian ruangan. Video disimpan di folder `Dokumentasi` dengan nama file `Dokum Tp7.webm`.

