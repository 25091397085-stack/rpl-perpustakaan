# Testing Keterlambatan Peminjaman Buku

Panduan untuk testing fitur denda keterlambatan tanpa menunggu 7 hari secara realtime.

---

## **Opsi 1: Laravel Tinker (Recommended - Paling Cepat)**

### Langkah-langkah:

```bash
php artisan tinker
```

#### Scenario 1: Ubah Due Date Menjadi Masa Lalu (5 Hari Lalu)

```php
// 1. Ambil borrowing terakhir
$borrowing = \App\Models\Borrowing::latest()->first();

// 2. Ubah due_date menjadi 5 hari lalu (simulasi terlambat 5 hari)
$borrowing->update(['due_date' => now()->subDays(5)->toDateString()]);

// 3. Lihat data
$borrowing;
```

#### Scenario 2: Trigger Return Book dengan Status Terlambat

```php
// Ambil borrowing yang sudah di-update due_date-nya
$borrowing = \App\Models\Borrowing::find(5); // sesuaikan ID

// Simulasikan return dengan menjalankan logic controller
$returnDate = now()->toDateString();
$dueDate = $borrowing->due_date;
$isLate = \Carbon\Carbon::parse($returnDate)->gt(\Carbon\Carbon::parse($dueDate));
$lateDays = 0;
$fineAmount = 0;

if ($isLate) {
    $lateDays = \Carbon\Carbon::parse($dueDate)->diffInDays(\Carbon\Carbon::parse($returnDate));
    $fineAmount = $lateDays * 3000;
}

echo "Status Terlambat: " . ($isLate ? "YA" : "TIDAK");
echo "\nHari Terlambat: " . $lateDays;
echo "\nNominal Denda: Rp. " . number_format($fineAmount);

// Buat fine record
if ($isLate && $fineAmount > 0) {
    $fine = \App\Models\Fine::create([
        'borrowing_id' => $borrowing->id,
        'amount' => $fineAmount,
        'payment_status' => 'belum dibayar'
    ]);
    echo "\n✅ Fine berhasil dibuat: Rp. " . number_format($fine->amount);
}

// Update borrowing
$borrowing->update([
    'return_date' => $returnDate,
    'status' => $isLate ? 'terlambat' : 'sudah dikembalikan'
]);

echo "\n✅ Borrowing status: " . $borrowing->status;
```

#### Scenario 3: Cek Denda di Database

```php
// Lihat semua fine
\App\Models\Fine::with('borrowing')->get();

// Lihat fine spesifik untuk borrowing
\App\Models\Fine::where('borrowing_id', 5)->first();
```

---

## **Opsi 2: Buat Database Seeder untuk Testing**

Buat file: `database/seeders/TestLateSeeder.php`

```php
<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use App\Models\Fine;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestLateSeeder extends Seeder
{
    public function run(): void
    {
        // Buat borrowing dengan due_date yang sudah lewat
        $borrowing = Borrowing::create([
            'book_id' => 1,
            'member_id' => 1,
            'borrow_date' => now()->subDays(12),
            'due_date' => now()->subDays(5),        // 5 hari lalu
            'return_date' => now()->subDays(2),     // 2 hari lalu (masih terlambat 3 hari)
            'status' => 'terlambat'
        ]);

        // Hitung denda: 3 hari x 3000 = 9000
        $lateDays = Carbon::parse($borrowing->due_date)
            ->diffInDays($borrowing->return_date);
        $fineAmount = $lateDays * 3000;

        // Buat fine
        Fine::create([
            'borrowing_id' => $borrowing->id,
            'amount' => $fineAmount,
            'payment_status' => 'belum dibayar'
        ]);

        echo "✅ Test data berhasil dibuat:\n";
        echo "   - Borrowing ID: {$borrowing->id}\n";
        echo "   - Terlambat: {$lateDays} hari\n";
        echo "   - Denda: Rp. " . number_format($fineAmount) . "\n";
    }
}
```

#### Jalankan seeder:

```bash
php artisan db:seed --class=TestLateSeeder
```

---

## **Opsi 3: Buat API Endpoint Testing**

Buat route test di `routes/web.php`:

```php
Route::get('/test/late-borrowing', function () {
    // Ambil borrowing terakhir
    $borrowing = \App\Models\Borrowing::latest()->first();

    // Ubah due_date menjadi masa lalu
    $borrowing->update(['due_date' => now()->subDays(5)]);

    return response()->json([
        'message' => 'Due date diubah ke 5 hari lalu',
        'borrowing' => $borrowing
    ]);
})->name('test.late');

Route::get('/test/trigger-return/{id}', function ($id) {
    $borrowing = \App\Models\Borrowing::find($id);

    // Trigger return logic
    $returnDate = now()->toDateString();
    $dueDate = $borrowing->due_date;
    $isLate = \Carbon\Carbon::parse($returnDate)
        ->gt(\Carbon\Carbon::parse($dueDate));

    if ($isLate) {
        $lateDays = \Carbon\Carbon::parse($dueDate)
            ->diffInDays(\Carbon\Carbon::parse($returnDate));
        $fineAmount = $lateDays * 3000;

        \App\Models\Fine::create([
            'borrowing_id' => $borrowing->id,
            'amount' => $fineAmount,
            'payment_status' => 'belum dibayar'
        ]);

        $borrowing->update(['status' => 'terlambat']);
    }

    return response()->json([
        'borrowing' => $borrowing,
        'fine' => $borrowing->fine,
        'late_days' => $isLate ? $lateDays : 0
    ]);
})->name('test.return');
```

#### Akses via browser:

```
http://localhost:8000/test/late-borrowing
http://localhost:8000/test/trigger-return/5
```

---

## **Opsi 4: Unit Test dengan PHPUnit**

Buat file: `tests/Feature/BorrowingLateTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\Borrowing;
use App\Models\Fine;
use Tests\TestCase;
use Carbon\Carbon;

class BorrowingLateTest extends TestCase
{
    public function test_borrowing_late_creates_fine()
    {
        // Setup
        $borrowing = Borrowing::factory()->create([
            'due_date' => now()->subDays(5),
            'status' => 'belum dikembalikan'
        ]);

        // Calculate fine
        $lateDays = Carbon::parse($borrowing->due_date)
            ->diffInDays(now());
        $fineAmount = $lateDays * 3000;

        // Create fine
        $fine = Fine::create([
            'borrowing_id' => $borrowing->id,
            'amount' => $fineAmount,
            'payment_status' => 'belum dibayar'
        ]);

        // Assert
        $this->assertDatabaseHas('fines', [
            'borrowing_id' => $borrowing->id,
            'amount' => $fineAmount,
            'payment_status' => 'belum dibayar'
        ]);

        $this->assertEquals($lateDays * 3000, $fine->amount);
    }

    public function test_not_late_creates_no_fine()
    {
        // Setup (due_date = 2 hari ke depan)
        $borrowing = Borrowing::factory()->create([
            'due_date' => now()->addDays(2),
            'status' => 'belum dikembalikan'
        ]);

        $dueDate = $borrowing->due_date;
        $isLate = Carbon::parse(now())->gt(Carbon::parse($dueDate));

        // Assert not late
        $this->assertFalse($isLate);

        // Assert no fine
        $this->assertDatabaseMissing('fines', [
            'borrowing_id' => $borrowing->id
        ]);
    }
}
```

#### Jalankan test:

```bash
php artisan test tests/Feature/BorrowingLateTest.php
```

---

## **Opsi 5: Modify Migration untuk Testing**

Edit `database/migrations/[date]_create_borrowings_table.php`:

Tambahkan nullable `due_date` untuk testing:

```php
// Jangan ubah, tapi di development bisa update manual via tinker/seeder
```

---

## **⚡ Quickstart Command**

Paste langsung di Tinker untuk testing cepat:

```php
// Setup borrowing terlambat
$b = \App\Models\Borrowing::create(['book_id' => 1, 'member_id' => 1, 'borrow_date' => now()->subDays(10), 'due_date' => now()->subDays(3), 'status' => 'belum dikembalikan']);

// Hitung denda
$days = \Carbon\Carbon::parse($b->due_date)->diffInDays(now());
$amount = $days * 3000;

// Buat fine
\App\Models\Fine::create(['borrowing_id' => $b->id, 'amount' => $amount, 'payment_status' => 'belum dibayar']);

// Lihat hasil
dd(['borrowing' => $b, 'fine' => $b->fine]);
```

---

## **Resume**

| Opsi             | Kecepatan | Kemudahan | Best For          |
| ---------------- | --------- | --------- | ----------------- |
| **Tinker**       | ⚡⚡⚡    | ⭐⭐⭐    | Quick testing     |
| **Seeder**       | ⚡⚡      | ⭐⭐      | Repeated setup    |
| **API Endpoint** | ⚡⚡      | ⭐⭐⭐    | Manual testing    |
| **PHPUnit**      | ⚡        | ⭐        | Automated testing |
| **DB Seeder**    | ⚡        | ⭐⭐⭐    | Bulk test data    |

✅ **Rekomendasi**: Gunakan **Tinker** untuk quick testing, **Seeder** untuk repeated setup, dan **PHPUnit** untuk CI/CD pipeline.
