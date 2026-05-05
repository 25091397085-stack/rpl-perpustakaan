# Sistem Informasi Perpustakaan (Mini Project)

Selamat datang di repositori **Sistem Informasi Perpustakaan**, sebuah aplikasi web berbasis Laravel yang dikembangkan untuk memanajemen peminjaman buku, katalog buku, dan perhitungan denda keterlambatan pengembalian secara otomatis.

## 🌟 Fitur Utama
- **Role-Based Access Control (RBAC)**: Dibedakan menjadi hak akses `Admin` dan `Member`.
- **Manajemen Katalog Buku & Kategori**: Admin dapat menambahkan, mengubah, atau menghapus data buku dan kategori.
- **Sistem Peminjaman Terpusat**: Member dapat meminjam buku (maksimal 3) dan sistem akan mengurangi stok secara otomatis.
- **Kalkulasi Denda Keterlambatan**: Sistem secara otomatis menghitung denda (Rp3.000/hari) bagi member yang terlambat mengembalikan buku.
- **Dashboard Interaktif**: Informasi ringkas mengenai status peminjaman, jumlah denda, dan riwayat di masing-masing akun.

---

## 📚 Dokumentasi Proyek

Untuk memahami alur kerja (*workflow*), Diagram Sekuensial (*Sequence Diagrams*), Data Flow Diagram (DFD), Activity Diagram, dan pemodelan Database (DBML & ERD) yang digunakan dalam aplikasi ini, silakan menuju ke halaman Dokumentasi resmi kami dengan mengklik tombol di bawah ini:

<br>
<p align="center">
  <a href="documentation.md">
    <img src="https://img.shields.io/badge/📖_Baca_Dokumentasi_Lengkap-10b981?style=for-the-badge&logo=markdown&logoColor=white" alt="Baca Dokumentasi">
  </a>
</p>
<br>

---

## 🚀 Cara Menjalankan Project (Instalasi Lokal)

1. *Clone* repositori ini ke komputer Anda.
2. Buka terminal dan arahkan ke direktori proyek.
3. Jalankan perintah `composer install` untuk menginstal seluruh dependensi PHP (Laravel).
4. Buat file lingkungan baru dengan menyalin `.env.example` menjadi `.env`.
5. *Generate key* aplikasi dengan menjalankan `php artisan key:generate`.
6. Sesuaikan kredensial *database* Anda di dalam file `.env`.
7. Jalankan perintah migrasi dan *seeder* untuk mengisi *database*: `php artisan migrate --seed`.
8. Nyalakan *server* lokal menggunakan perintah `php artisan serve`.
9. Akses aplikasi melalui *browser* pada tautan `http://localhost:8000`.

---
*Dibuat untuk keperluan pemenuhan Tugas / Mini Project Rekayasa Perangkat Lunak.*
