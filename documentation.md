# Dokumentasi dan Analisis Sistem Informasi Perpustakaan

## 1. Analisis `routes/web.php`
Sistem perutean (routing) pada aplikasi ini telah memisahkan akses pengguna berdasarkan *Role-Based Access Control* (RBAC) menggunakan *middleware* `auth` dan `role`:
- **Admin Only (`role:admin`)**: Admin memiliki hak akses penuh (CRUD) terhadap resource master, yaitu `members`, `categories`, dan `books`. Admin juga memiliki rute spesifik untuk memproses peminjaman, yaitu menerima pengembalian buku (`borrowings.return`) dan menandai keterlambatan manual (`borrowings.late`).
- **Admin & Member**: Rute yang bisa diakses oleh kedua entitas (dengan perbedaan data yang ditampilkan melalui kontroler). Ini mencakup melihat daftar peminjaman (`borrowings.index`), melihat dashboard/katalog (`member.home`), melakukan peminjaman buku (`borrow.book`), serta melihat dan membayar denda (`fines.index`, `fines.pay`, `fines.late`).
- **Profile**: Rute standar untuk manajemen akun pengguna (Edit, Update, Destroy) yang dapat diakses semua *authenticated user*.

## 2. Analisis MVC (Model, View, Controller) dan Migrasi
- **Migrasi & Model**: 
  - `User`: Menangani akun login.
  - `Member`: Berisi detail profil spesifik pengguna yang mendaftar sebagai anggota perpustakaan (berelasi dengan `User`).
  - `Category`: Data referensi kategori buku.
  - `Book`: Entitas buku yang mencakup informasi *title, author, cover, stock,* dan *sinopsis*. Berelasi dengan `Category`.
  - `Borrowing`: Entitas transaksional untuk mencatat proses peminjaman. Memiliki properti *borrow_date, due_date, return_date,* dan *status*. Berelasi dengan `Book` dan `Member`.
  - `Fine`: Entitas transaksional denda yang terhubung ke `Borrowing`. Memiliki properti *amount* (nominal denda) dan *payment_status* (belum/sudah dibayar).
- **Controller**:
  - `BookController, CategoryController, MemberController`: Meng-handle operasi standar CRUD untuk data master. Admin menggunakan ini untuk mengelola perpustakaan.
  - `BorrowingController`: Mengatur logika bisnis peminjaman. Terdapat validasi ketat (maksimal pinjam 3 buku, stok buku harus > 0, tidak boleh meminjam buku yang sama secara ganda). Controller ini juga memproses pengembalian buku (menambah kembali stok) dan otomatis mengkalkulasi denda (Rp3.000/hari) jika terlambat.
  - `FineController`: Menangani penampilan data denda. Untuk Member, *query* difilter agar hanya memunculkan dendanya sendiri. Controller ini juga menyediakan *method* untuk simulasi pembayaran denda (`pay`).
- **View**: Direktori dipisahkan berdasar fungsionalitas dan aktor (misal `admin.home`, `members.home`). Tampilan menggunakan sistem *templating* Blade dari Laravel untuk merender data secara dinamis dari Controller.

## 3. Analisis Alur Kerja Aplikasi (Workflow)

**Alur Kerja Admin:**
1. **Dashboard**: Admin login dan melihat statistik jumlah buku, kategori, dan peminjaman yang masih aktif.
2. **Manajemen Master Data**: Admin bertugas mengelola katalog (Tambah, Edit, Hapus buku dan kategorinya) serta mengelola data anggota (Member).
3. **Pemrosesan Pengembalian**: Saat member mengembalikan buku, admin mencari data peminjaman di sistem dan menekan *Return*. Sistem akan memeriksa apakah tanggal saat ini melewati batas pengembalian (`due_date`).
4. **Kalkulasi Denda**: Jika pengembalian terlambat, sistem otomatis meng-generate data `Fine` dan menambahkannya ke tagihan member terkait. Stok buku kembali bertambah.

**Alur Kerja Member:**
1. **Katalog & Dashboard**: Member login dan melihat katalog buku yang tersedia beserta riwayat ringkas pinjamannya.
2. **Peminjaman Buku**: Member dapat menekan tombol pinjam pada buku. Sistem memvalidasi ketersediaan stok dan limitasi (maks. 3 buku aktif). Jika berhasil, stok buku berkurang 1.
3. **Riwayat Peminjaman**: Member dapat memantau buku apa saja yang belum mereka kembalikan beserta tanggal jatuh temponya.
4. **Penyelesaian Denda**: Jika member terlambat mengembalikan buku dan dikenakan denda oleh admin, member dapat melihat rincian dendanya di halaman *Fines* dan melakukan pelunasan tagihan.

---

## 4. DFD (Data Flow Diagram) Level 0 & Level 1

Berikut adalah pemodelan UML/Diagram aliran data dari aplikasi menggunakan Mermaid.

### DFD Level 0 (Context Diagram)
Menggambarkan interaksi sistem perpustakaan secara keseluruhan dengan entitas eksternal (Admin dan Member).

```mermaid
graph TD
    %% Entitas Eksternal
    Admin((Admin))
    Member((Member))

    %% Sistem
    System{{"Sistem Informasi\nPerpustakaan"}}

    %% Aliran Data Admin
    Admin -- "Data Buku, Data Kategori,\nData Member, Konfirmasi Pengembalian" --> System
    System -- "Laporan Peminjaman,\nStatus Denda, Statistik Dashboard" --> Admin

    %% Aliran Data Member
    Member -- "Request Peminjaman,\nPelunasan Denda, Update Profil" --> System
    System -- "Katalog Buku, Status Peminjaman,\nTagihan Denda" --> Member
```

## 5. Sequence Diagram

Berikut adalah diagram sekuensial (Sequence Diagram) yang memodelkan interaksi antara aktor (User/Admin/Member) dan sistem perpustakaan dari awal proses hingga operasi terselesaikan ke database.

### 5.1. Sequence Diagram Login
Alur saat pengguna (Admin atau Member) melakukan autentikasi ke dalam sistem.

```mermaid
sequenceDiagram
    actor User as Admin / Member
    participant System as Sistem Perpustakaan
    participant DB as Database

    User->>System: Input Email & Password (Login)
    System->>DB: Query User berdasarkan Email
    DB-->>System: Return Data User & Password Hash
    System->>System: Validasi Password & Role (Auth)
    alt Kredensial Valid
        System->>User: Redirect ke Dashboard (Sesuai Role)
    else Kredensial Tidak Valid
        System-->>User: Tampilkan Error (Kredensial salah)
    end
```

### 5.2. Sequence Diagram Peminjaman Buku
Alur ketika Member mengajukan peminjaman sebuah buku dari katalog.

```mermaid
sequenceDiagram
    actor Member
    participant Controller as BorrowingController
    participant DB as Database

    Member->>Controller: Request Borrow Buku (book_id)
    Controller->>DB: Cek Data Member (limit pinjaman aktif)
    DB-->>Controller: Return Jumlah Peminjaman (Max 3)
    
    Controller->>DB: Cek Ketersediaan Stok Buku
    DB-->>Controller: Return Stok Buku (> 0)
    
    alt Validasi Gagal (Limit tercapai atau Stok habis)
        Controller-->>Member: Return Error Message (Kembali ke halaman sebelumnya)
    else Validasi Berhasil
        Controller->>DB: Insert Data Borrowing (status: belum dikembalikan)
        Controller->>DB: Update Table Book (stock - 1)
        DB-->>Controller: Konfirmasi Commit Transaksi
        Controller-->>Member: Return Success Message (Buku dipinjam)
    end
```

### 5.3. Sequence Diagram Pengembalian Buku
Alur ketika Admin memproses pengembalian buku dari Member, yang juga akan mengecek apakah terjadi keterlambatan dan secara otomatis membuat denda.

```mermaid
sequenceDiagram
    actor Admin
    participant Controller as BorrowingController
    participant DB as Database

    Admin->>Controller: Klik Return Buku (borrowing_id)
    Controller->>DB: Ambil Data Peminjaman (due_date)
    DB-->>Controller: Return Data Peminjaman
    
    Controller->>Controller: Cek Keterlambatan (today_date > due_date)
    
    alt Terlambat
        Controller->>Controller: Hitung kalkulasi denda (selisih hari * Rp 3000)
        Controller->>DB: Insert Data Fine (amount, payment_status: belum dibayar)
        Controller->>DB: Update Status Borrowing (status: terlambat, return_date)
    else Tepat Waktu
        Controller->>DB: Update Status Borrowing (status: sudah dikembalikan, return_date)
    end
    
    Controller->>DB: Update Table Book (stock + 1, status: tersedia)
    DB-->>Controller: Konfirmasi Update Berhasil
    Controller-->>Admin: Return Success Message (+ Info nominal denda jika ada)
```

### 5.4. Sequence Diagram Pembayaran Denda
Alur ketika Member melihat daftar denda dan menekan tombol pembayaran pada suatu tanggungan denda.

```mermaid
sequenceDiagram
    actor Member
    participant Controller as FineController
    participant DB as Database

    Member->>Controller: Lihat Halaman Denda (/fines)
    Controller->>DB: Query tabel Fines khusus untuk member_id tersebut
    DB-->>Controller: Return Koleksi Denda
    Controller-->>Member: Tampilkan View Daftar Denda
    
    Member->>Controller: Klik Bayar Denda (fine_id)
    Controller->>DB: Update Data Fine (payment_status: sudah dibayar)
    DB-->>Controller: Konfirmasi Update
    Controller-->>Member: Return Success Message (Pembayaran denda berhasil)
```
### DFD Level 1
Memecah Sistem Utama ke dalam proses-proses inti (Manajemen Master, Peminjaman, dan Denda).

```mermaid
graph TD
    %% Entitas Eksternal
    Admin((Admin))
    Member((Member))

    %% Proses
    P1(("1.0\nManajemen\nMaster Data"))
    P2(("2.0\nProses\nPeminjaman"))
    P3(("3.0\nProses\nPengembalian\n& Denda"))

    %% Data Stores
    DS1[("D1: Books &\nCategories")]
    DS2[("D2: Members")]
    DS3[("D3: Borrowings")]
    DS4[("D4: Fines")]

    %% Aliran dari/ke Admin
    Admin -- "Input Data Buku/Kategori" --> P1
    Admin -- "Input Data Member" --> P1
    P1 -- "Info Buku & Kategori" --> Admin
    
    Admin -- "Konfirmasi Return/Late" --> P3
    P3 -- "Laporan Peminjaman/Denda" --> Admin

    %% Aliran dari/ke Member
    Member -- "Pilih Buku" --> P2
    P2 -- "Notifikasi Berhasil/Gagal" --> Member
    
    P3 -- "Tagihan Denda" --> Member
    Member -- "Konfirmasi Bayar Denda" --> P3

    %% Interaksi dengan Data Store (Master Data)
    P1 <--> DS1
    P1 <--> DS2

    %% Interaksi Peminjaman
    P2 -- "Cek Ketersediaan" --> DS1
    P2 -- "Cek Kuota Pinjam" --> DS2
    P2 -- "Update Stok (-1)" --> DS1
    P2 -- "Insert Record" --> DS3

    %% Interaksi Pengembalian & Denda
    P3 -- "Update Status & Return Date" --> DS3
    P3 -- "Update Stok (+1)" --> DS1
    P3 -- "Hitung Keterlambatan &\nInsert Denda" --> DS4
    DS3 -- "Ambil Data Peminjaman\n& Due Date" --> P3
    DS4 -- "Ambil Data Denda" --> P3
```
