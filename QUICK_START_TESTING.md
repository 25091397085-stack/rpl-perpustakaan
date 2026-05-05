# 🚀 Quick Start Testing Late Borrowing

## **Metode 1: Menggunakan API Endpoints (Recommended)**

Semua endpoint ini sudah siap di routes/test.php dan bisa diakses langsung dari browser atau Postman.

### Langkah-langkah:

#### 1️⃣ Login terlebih dahulu

```
http://localhost:8000/login
```

#### 2️⃣ Buat data borrowing terlambat

```
http://localhost:8000/test/late-borrowing/create-late-data
```

**Response:**

```json
{
    "message": "✅ Borrowing terlambat 5 hari berhasil dibuat",
    "borrowing": {
        "id": 1,
        "book_id": 1,
        "member_id": 1,
        "due_date": "2026-04-24",
        "status": "belum dikembalikan"
    },
    "note": "Kunjungi /test/late-borrowing/1/check-and-fine untuk membuat fine"
}
```

#### 3️⃣ Check dan buat fine

Ganti `1` dengan ID borrowing dari response sebelumnya:

```
http://localhost:8000/test/late-borrowing/1/check-and-fine
```

**Response:**

```json
{
    "message": "✅ Borrowing terlambat, fine telah dibuat",
    "borrowing": {
        "id": 1,
        "status": "terlambat",
        "due_date": "2026-04-24"
    },
    "is_late": true,
    "late_days": 5,
    "fine_amount": 15000,
    "fine": {
        "id": 1,
        "borrowing_id": 1,
        "amount": 15000,
        "payment_status": "belum dibayar"
    }
}
```

#### 4️⃣ Lihat semua fine

```
http://localhost:8000/test/fines/list-all
```

#### 5️⃣ Lihat semua borrowing dengan status

```
http://localhost:8000/test/late-borrowing/list-all
```

#### 6️⃣ Batch check (auto create fine untuk semua yang terlambat)

```
http://localhost:8000/test/late-borrowing/batch-check
```

#### 7️⃣ Reset semua test data (hati-hati!)

```
http://localhost:8000/test/reset
```

---

## **Metode 2: Menggunakan Seeder**

### Langkah-langkah:

#### 1️⃣ Jalankan seeder

```bash
php artisan db:seed --class=TestLateSeeder
```

**Output:**

```
=== Testing Late Borrowing Seeder ===

📌 Scenario 1: Terlambat 3 hari
   ID Peminjaman: 1
   Due Date: 2026-04-26
   Terlambat: 3 hari
   Denda: Rp. 9,000
   ✅ Fine berhasil dibuat

📌 Scenario 2: Terlambat 5 hari
   ID Peminjaman: 2
   Due Date: 2026-04-24
   Terlambat: 5 hari
   Denda: Rp. 15,000
   ✅ Fine berhasil dibuat

📌 Scenario 3: Tidak terlambat
   ID Peminjaman: 3
   Due Date: 2026-05-01
   Status: Belum terlambat
   ✅ Tidak ada fine

=== ✅ Test data berhasil dibuat ===

untuk cek fine, buka: http://localhost:8000/fines
```

#### 2️⃣ Cek fine di UI

```
http://localhost:8000/fines
```

---

## **Metode 3: Menggunakan Laravel Tinker**

### Langkah-langkah:

```bash
php artisan tinker
```

#### Copy-paste commands berikut:

```php
// 1. Buat borrowing terlambat
$b = \App\Models\Borrowing::create([
  'book_id' => 1,
  'member_id' => 1,
  'borrow_date' => now()->subDays(10),
  'due_date' => now()->subDays(5),
  'status' => 'belum dikembalikan'
]);

// 2. Hitung denda
$days = \Carbon\Carbon::parse($b->due_date)->diffInDays(now());
$amount = $days * 3000;

// 3. Buat fine
$f = \App\Models\Fine::create([
  'borrowing_id' => $b->id,
  'amount' => $amount,
  'payment_status' => 'belum dibayar'
]);

// 4. Lihat hasil
dd(['borrowing' => $b, 'fine' => $f]);
```

#### Keluar dari tinker

```php
exit
```

---

## **Metode 4: Menjalankan Unit Tests**

### Langkah-langkah:

```bash
# Jalankan semua test
php artisan test tests/Feature/BorrowingLateTest.php

# Jalankan test spesifik
php artisan test tests/Feature/BorrowingLateTest.php --filter test_late_borrowing_creates_fine

# Jalankan dengan verbose
php artisan test tests/Feature/BorrowingLateTest.php -v

# Jalankan dengan coverage
php artisan test tests/Feature/BorrowingLateTest.php --coverage
```

---

## **Ringkasan Testing**

| Metode            | Setup          | Kecepatan | Best For             |
| ----------------- | -------------- | --------- | -------------------- |
| **API Endpoints** | ⚡ Instant     | ⚡⚡⚡    | Quick manual testing |
| **Seeder**        | ⚡⚡ 10 detik  | ⚡⚡      | Repeated setup       |
| **Tinker**        | ⚡⚡⚡ 5 detik | ⚡⚡⚡    | Ad-hoc testing       |
| **Unit Tests**    | ⚡ Setup       | ⚡        | Automated CI/CD      |

---

## **Troubleshooting**

### ❌ Error: "Book atau Member tidak ditemukan"

**Solusi:**

```bash
# Pastikan ada data di database
php artisan db:seed
```

### ❌ Error: "404 Not Found" di API endpoint

**Solusi:**

```bash
# Pastikan sudah login
# Dan server running di http://localhost:8000
```

### ❌ Fine tidak terbuat

**Solusi:**
Pastikan `due_date` lebih kecil dari tanggal sekarang:

```php
$borrowing->due_date < now()  // Harus true
```

---

## **Demo Lengkap (Copy-Paste)**

```bash
# 1. Terminal 1: Start server
php artisan serve

# 2. Terminal 2: Login dan test via API
# Buka browser: http://localhost:8000/login
# Setelah login, buka: http://localhost:8000/test/late-borrowing/create-late-data
# Lalu: http://localhost:8000/test/late-borrowing/1/check-and-fine
# Terakhir: http://localhost:8000/fines
```

✅ **Selesai! Fine sudah dibuat dan bisa dilihat di halaman fines.**
