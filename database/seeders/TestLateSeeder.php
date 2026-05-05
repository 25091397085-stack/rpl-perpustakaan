<?php

namespace Database\Seeders;

use App\Models\Borrowing;
use App\Models\Fine;
use App\Models\Book;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestLateSeeder extends Seeder
{
  /**
   * Run the database seeds.
   */
  public function run(): void
  {
    echo "\n=== Testing Late Borrowing Seeder ===\n";

    // Cek apakah ada member dan book
    $member = Member::first();
    $book = Book::first();

    if (!$member || !$book) {
      echo "❌ Member atau Book tidak ditemukan. Jalankan DatabaseSeeder terlebih dahulu.\n";
      return;
    }

    // Scenario 1: Terlambat 3 hari
    echo "\n📌 Scenario 1: Terlambat 3 hari\n";
    $borrowing1 = Borrowing::create([
      'book_id' => $book->id,
      'member_id' => $member->id,
      'borrow_date' => now()->subDays(10),
      'due_date' => now()->subDays(3),      // 3 hari lalu
      'return_date' => null,
      'status' => 'belum dikembalikan'
    ]);

    $lateDays1 = 3;
    $fineAmount1 = $lateDays1 * 3000;

    Fine::create([
      'borrowing_id' => $borrowing1->id,
      'amount' => $fineAmount1,
      'payment_status' => 'belum dibayar'
    ]);

    echo "   ID Peminjaman: {$borrowing1->id}\n";
    echo "   Due Date: {$borrowing1->due_date}\n";
    echo "   Terlambat: {$lateDays1} hari\n";
    echo "   Denda: Rp. " . number_format($fineAmount1) . "\n";
    echo "   ✅ Fine berhasil dibuat\n";

    // Scenario 2: Terlambat 5 hari
    echo "\n📌 Scenario 2: Terlambat 5 hari\n";
    $borrowing2 = Borrowing::create([
      'book_id' => $book->id,
      'member_id' => $member->id,
      'borrow_date' => now()->subDays(12),
      'due_date' => now()->subDays(5),      // 5 hari lalu
      'return_date' => null,
      'status' => 'belum dikembalikan'
    ]);

    $lateDays2 = 5;
    $fineAmount2 = $lateDays2 * 3000;

    Fine::create([
      'borrowing_id' => $borrowing2->id,
      'amount' => $fineAmount2,
      'payment_status' => 'belum dibayar'
    ]);

    echo "   ID Peminjaman: {$borrowing2->id}\n";
    echo "   Due Date: {$borrowing2->due_date}\n";
    echo "   Terlambat: {$lateDays2} hari\n";
    echo "   Denda: Rp. " . number_format($fineAmount2) . "\n";
    echo "   ✅ Fine berhasil dibuat\n";

    // Scenario 3: Tidak terlambat (due_date masih depan)
    echo "\n📌 Scenario 3: Tidak terlambat (Due date masih 2 hari ke depan)\n";
    $borrowing3 = Borrowing::create([
      'book_id' => $book->id,
      'member_id' => $member->id,
      'borrow_date' => now(),
      'due_date' => now()->addDays(2),      // 2 hari ke depan
      'return_date' => null,
      'status' => 'belum dikembalikan'
    ]);

    echo "   ID Peminjaman: {$borrowing3->id}\n";
    echo "   Due Date: {$borrowing3->due_date}\n";
    echo "   Status: Belum terlambat\n";
    echo "   ✅ Tidak ada fine\n";

    echo "\n=== ✅ Test data berhasil dibuat ===\n";
    echo "\nuntuk cek fine, buka: http://localhost:8000/fines\n\n";
  }
}
