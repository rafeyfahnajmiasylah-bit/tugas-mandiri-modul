# Tugas Mandiri Modul 6
## Perancangan ERD E-Library Kampus

Nama   :Rafeyfah Najmi Asylah
NIM    :D121241111

## 1. Deskripsi Sistem

E-Library Kampus merupakan sistem basis data yang digunakan untuk
mengelola data mahasiswa, buku, penerbit, serta transaksi peminjaman
dan pengembalian buku di perpustakaan kampus.

## 2. Entitas dan Atribut

Sistem E-Library memiliki empat entitas utama, yaitu Mahasiswa, Buku,
Penerbit, dan Transaksi Peminjaman.

### 2.1 Entitas Mahasiswa

| Atribut        | Tipe Data    | Keterangan              |
|----------------|--------------|-------------------------|
| nim            | VARCHAR(15)  | Primary Key             |
| nama_mahasiswa | VARCHAR(100) | Nama mahasiswa          |
| program_studi  | VARCHAR(100) | Program studi           |
| email          | VARCHAR(100) | Email mahasiswa         |
| no_telepon     | VARCHAR(15)  | Nomor telepon mahasiswa |

### 2.2 Entitas Penerbit

| Atribut        | Tipe Data         | Keterangan             |
|----------------|-------------------|------------------------|
| id_penerbit    | INT               | Primary Key            |
| nama_penerbit  | VARCHAR(100)      | Nama penerbit          |
| alamat         | VARCHAR(200)      | Alamat penerbit        |
| no_telepon     | VARCHAR(15)       | Nomor telepon penerbit |

### 2.3 Entitas Buku

| Atribut        | Tipe Data    | Keterangan                    |
|----------------|--------------|-------------------------------|
| id_buku        | INT          | Primary Key                   |
| judul_buku     | VARCHAR(150) | Judul buku                    |
| penulis        | VARCHAR(100) | Nama penulis                  |
| tahun_terbit   | YEAR         | Tahun terbit                  |   
| kategori       | VARCHAR(50)  | Kategori buku                 |
| id_penerbit    | INT          | Foreign Key ke tabel Penerbit |

### 2.4 Entitas Transaksi Peminjaman

| Atribut             | Tipe Data   | Keterangan                    |
|---------------------|-------------|-------------------------------|
| id_peminjaman       | INT         | Primary Key                   |
| nim                 | VARCHAR(15) | Foreign Key ke tabel Mahasiswa|
| id_buku             | INT         | Foreign Key ke tabel Buku     |
| tanggal_peminjaman  | DATE        | Tanggal buku dipinjam         |
| tanggal_jatuh_tempo | DATE        | Batas waktu pengembalian      |    
| tanggal_pengembalian| DATE        | Tanggal buku dikembalikan     |