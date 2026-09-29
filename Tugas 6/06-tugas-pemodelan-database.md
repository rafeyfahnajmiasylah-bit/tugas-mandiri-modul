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

## 3. Normalisasi Database

Normalisasi dilakukan untuk mengurangi redundansi data dan menjaga
konsistensi data dalam basis data. Proses normalisasi dilakukan secara
bertahap mulai dari Unnormalized Form (UNF), First Normal Form (1NF),
Second Normal Form (2NF), hingga Third Normal Form (3NF).

### 3.1 Unnormalized Form (UNF)

Pada bentuk UNF, data mahasiswa, buku, penerbit, dan peminjaman masih
disimpan dalam satu tabel. Pada kondisi ini, satu mahasiswa dapat
memiliki lebih dari satu data buku dalam satu transaksi sehingga
terdapat atribut yang memiliki lebih dari satu nilai.

Contoh:

| NIM        | Nama Mahasiswa | Program Studi | Data Buku                                                   | Data Peminjaman                                  |
|------------|----------------|---------------|-------------------------------------------------------------|--------------------------------------------------|
| D121241111 | Andi           | Informatika   | B001 - Basis Data - Erlangga; B002 - Pemrograman Web - Andi | 01-09-2026 - 07-09-2026; 03-09-2026 - 10-09-2026 |

Pada tabel tersebut, Data Buku dan Data Peminjaman masih memiliki
lebih dari satu nilai dalam satu baris. Oleh karena itu, data belum
memenuhi First Normal Form (1NF).

### 3.2 First Normal Form (1NF)

Pada tahap 1NF, setiap atribut harus memiliki nilai yang atomik.
Data yang sebelumnya memiliki lebih dari satu nilai dipisahkan menjadi beberapa baris.

| NIM        | Nama Mahasiswa | Program Studi | ID Buku | Judul Buku      | Penerbit | Tanggal Peminjaman | Tanggal Pengembalian|
|------------|----------------|---------------|---------|-----------------|----------|--------------------|---------------------|
| D121241111 | Andi           | Informatika   | B001    | Basis Data      | Erlangga | 01-09-2026         | 07-09-2026          |
| D121241111 | Andi           | Informatika   | B002    | Pemrograman Web | Andi     | 03-09-2026         | 10-09-2026          |

Setiap sel sekarang hanya memiliki satu nilai. Namun, masih terdapat
pengulangan data mahasiswa dan data buku. Data mahasiswa juga akan
terus berulang apabila mahasiswa melakukan beberapa peminjaman.

### 3.3 Second Normal Form (2NF)

Untuk mencapai 2NF, tabel harus sudah memenuhi 1NF dan setiap atribut
non-key harus bergantung sepenuhnya pada primary key.

Pada tahap ini, data dipisahkan berdasarkan entitas dan
ketergantungannya.

#### Tabel Mahasiswa

| NIM        | Nama Mahasiswa | Program Studi |
|------------|----------------|---------------|
| D121241111 | Andi           | Informatika   |

Atribut Nama Mahasiswa dan Program Studi bergantung pada NIM.

#### Tabel Buku

| ID Buku | Judul Buku      | Penerbit |
|---------|-----------------|----------|
| B001    | Basis Data      | Erlangga |
| B002    | Pemrograman Web | Andi     |

Atribut Judul Buku dan Penerbit bergantung pada ID Buku.

#### Tabel Peminjaman

| ID Peminjaman | NIM        | ID Buku | Tanggal Peminjaman | Tanggal Pengembalian |
|---------------|------------|---------|--------------------|----------------------|
| P001          | D121241111 | B001    | 01-09-2026         | 07-09-2026           |
| P002          | D121241111 | B002    | 03-09-2026         | 10-09-2026           |

Pada tahap 2NF, data mahasiswa dan buku sudah dipisahkan dari data
transaksi sehingga pengulangan data dapat dikurangi.

### 3.4 Third Normal Form (3NF)

Untuk mencapai 3NF, tabel harus sudah memenuhi 2NF dan tidak boleh
memiliki ketergantungan transitif.

Pada tabel Buku, atribut Penerbit sebenarnya merupakan informasi
tersendiri yang memiliki atribut lain seperti nama penerbit, alamat,
dan nomor telepon. Oleh karena itu, informasi penerbit dipisahkan
menjadi tabel Penerbit.

#### Tabel Penerbit

| ID Penerbit | Nama Penerbit | Alamat     | No. Telepon |
|-------------|---------------|------------|-------------|
| P001        | Erlangga      | Jakarta    | 021123456   |
| P002        | Andi          | Yogyakarta | 0274123456  |

#### Tabel Buku Setelah 3NF

| ID Buku | Judul Buku      | Penulis     | Tahun Terbit | Kategori    | ID Penerbit |
|---------|-----------------|-------------|--------------|-------------|-------------|
| B001    | Basis Data      | Abdul Kadir | 2024         | Informatika | P001        |
| B002    | Pemrograman Web | Budi Raharjo| 2023         | Pemrograman | P002        |

Dengan pemisahan tersebut, informasi penerbit tidak perlu disimpan
berulang pada tabel Buku. Tabel Buku cukup menyimpan ID Penerbit
sebagai foreign key yang mengacu pada tabel Penerbit.

### 3.5 Hasil Normalisasi

Setelah melalui proses UNF, 1NF, 2NF, dan 3NF, diperoleh empat
entitas utama:

1. Mahasiswa
2. Penerbit
3. Buku
4. Peminjaman

Setiap tabel memiliki primary key masing-masing dan hubungan antar
tabel akan menggunakan foreign key.

## 4. Rancangan Tabel Akhir

Berdasarkan hasil normalisasi hingga Third Normal Form (3NF), sistem
E-Library memiliki empat tabel utama, yaitu Mahasiswa, Penerbit, Buku,
dan Peminjaman.

### 4.1 Tabel Mahasiswa

| Nama Kolom    | Tipe Data   | Constraint | Keterangan              |
|---------------|-------------|------------|-------------------------|
| nim           | VARCHAR(15) | PRIMARY KEY| Nomor induk mahasiswa   |
| nama_mahasiswa| VARCHAR(100)| NOT NULL   | Nama mahasiswa          |
| program_studi | VARCHAR(100)| NOT NULL   | Program studi mahasiswa |
| email         | VARCHAR(100)| UNIQUE     | Email mahasiswa         |
| no_telepon    | VARCHAR(15) | -          | Nomor telepon mahasiswa |

### 4.2 Tabel Penerbit

| Nama Kolom    | Tipe Data   | Constraint | Keterangan            |
|---------------|-------------|------------|-----------------------|
| id_penerbit   | INT         | PRIMARY KEY| ID penerbit           |
| nama_penerbit | VARCHAR(100)| NOT NULL   | Nama penerbit         |
| alamat        | VARCHAR(200)| -          | Alamat penerbit       |
| no_telepon    | VARCHAR(15) | -          | Nomor telepon penerbit|

### 4.3 Tabel Buku

| Nama Kolom  | Tipe Data    | Constraint | Keterangan       |
|-------------|--------------|------------|------------------|
| id_buku     | INT          | PRIMARY KEY| ID buku          |
| judul_buku  | VARCHAR(150) | NOT NULL   | Judul buku       |
| penulis     | VARCHAR(100) | NOT NULL   | Nama penulis     |
| tahun_terbit| YEAR         | NOT NULL   | Tahun terbit buku|
| kategori    | VARCHAR(50)  | -          | Kategori buku    |
| id_penerbit | INT          | FOREIGN KEY| ID penerbit      |

### 4.4 Tabel Peminjaman

| Nama Kolom          | Tipe Data   | Constraint  | Keterangan             |
|---------------------|-------------|-------------|------------------------|
| id_peminjaman       | INT         | PRIMARY KEY | ID transaksi peminjaman|
| nim                 | VARCHAR(15) | FOREIGN KEY | NIM mahasiswa          |
| id_buku             | INT         | FOREIGN KEY | ID buku yang dipinjam  |
| tanggal_peminjaman  | DATE        | NOT NULL    | Tanggal peminjaman     |
| tanggal_jatuh_tempo | DATE        | NOT NULL    | Batas pengembalian     |
| tanggal_pengembalian| DATE        | NULL        | Tanggal pengembalian   |

### 4.5 Relasi Foreign Key

| Tabel      | Foreign Key | Tabel Referensi | Kolom Referensi |
|------------|-------------|-----------------|-----------------|
| Buku       | id_penerbit | Penerbit        | id_penerbit     |
| Peminjaman | nim         | Mahasiswa       | nim             |
| Peminjaman | id_buku     | Buku            | id_buku         |