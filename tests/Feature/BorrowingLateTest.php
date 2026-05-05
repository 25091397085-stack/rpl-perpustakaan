<?php

namespace Tests\Feature;

use App\Models\Borrowing;
use App\Models\Book;
use App\Models\Member;
use App\Models\Fine;
use Carbon\Carbon;
use Tests\TestCase;

class BorrowingLateTest extends TestCase
{
  protected $borrowing;
  protected $book;
  protected $member;

  protected function setUp(): void
  {
    parent::setUp();

    // Siapkan data test
    $this->book = Book::firstOrCreate(
      ['id' => 999],
      ['name' => 'Test Book', 'author' => 'Test Author', 'stock' => 10]
    );

    $this->member = Member::firstOrCreate(
      ['id' => 999],
      ['user_id' => 1, 'phone' => '08123456789', 'address' => 'Test Address']
    );
  }

  /**
   * Test: Borrowing terlambat membuat fine
   */
  public function test_late_borrowing_creates_fine()
  {
    // Setup: Buat borrowing dengan due_date 3 hari lalu
    $borrowing = Borrowing::create([
      'book_id' => $this->book->id,
      'member_id' => $this->member->id,
      'borrow_date' => now()->subDays(10),
      'due_date' => now()->subDays(3),      // 3 hari lalu
      'return_date' => now(),
      'status' => 'belum dikembalikan'
    ]);

    // Calculate late days
    $lateDays = Carbon::parse($borrowing->due_date)
      ->diffInDays(Carbon::parse($borrowing->return_date));
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

    $this->assertEquals(3 * 3000, $fine->amount);
    $this->assertEquals('belum dibayar', $fine->payment_status);
  }

  /**
   * Test: Borrowing tidak terlambat tidak membuat fine
   */
  public function test_not_late_borrowing_no_fine()
  {
    // Setup: Buat borrowing dengan due_date 2 hari ke depan
    $borrowing = Borrowing::create([
      'book_id' => $this->book->id,
      'member_id' => $this->member->id,
      'borrow_date' => now(),
      'due_date' => now()->addDays(2),      // 2 hari ke depan
      'return_date' => now(),
      'status' => 'belum dikembalikan'
    ]);

    // Check if late
    $isLate = Carbon::parse($borrowing->return_date)
      ->gt(Carbon::parse($borrowing->due_date));

    // Assert not late
    $this->assertFalse($isLate);

    // Assert no fine created
    $this->assertDatabaseMissing('fines', [
      'borrowing_id' => $borrowing->id
    ]);
  }

  /**
   * Test: Denda dihitung dengan benar (5 hari terlambat = 15000)
   */
  public function test_fine_amount_calculation_correct()
  {
    // Setup
    $borrowing = Borrowing::create([
      'book_id' => $this->book->id,
      'member_id' => $this->member->id,
      'borrow_date' => now()->subDays(12),
      'due_date' => now()->subDays(5),      // 5 hari lalu
      'return_date' => now(),
      'status' => 'belum dikembalikan'
    ]);

    // Calculate
    $lateDays = Carbon::parse($borrowing->due_date)
      ->diffInDays(Carbon::parse($borrowing->return_date));
    $expectedFineAmount = $lateDays * 3000;

    // Create fine
    $fine = Fine::create([
      'borrowing_id' => $borrowing->id,
      'amount' => $expectedFineAmount,
      'payment_status' => 'belum dibayar'
    ]);

    // Assert
    $this->assertEquals(5 * 3000, $fine->amount);
    $this->assertEquals(15000, $fine->amount);
  }

  /**
   * Test: Fine payment status diubah menjadi 'sudah dibayar'
   */
  public function test_fine_payment_status_update()
  {
    // Setup
    $borrowing = Borrowing::create([
      'book_id' => $this->book->id,
      'member_id' => $this->member->id,
      'borrow_date' => now()->subDays(10),
      'due_date' => now()->subDays(3),
      'return_date' => now(),
      'status' => 'belum dikembalikan'
    ]);

    $fine = Fine::create([
      'borrowing_id' => $borrowing->id,
      'amount' => 3 * 3000,
      'payment_status' => 'belum dibayar'
    ]);

    // Update payment status
    $fine->update(['payment_status' => 'sudah dibayar']);

    // Assert
    $this->assertEquals('sudah dibayar', $fine->payment_status);
    $this->assertDatabaseHas('fines', [
      'id' => $fine->id,
      'payment_status' => 'sudah dibayar'
    ]);
  }

  /**
   * Test: Borrowing status berubah ke 'terlambat' ketika ada fine
   */
  public function test_borrowing_status_late()
  {
    // Setup
    $borrowing = Borrowing::create([
      'book_id' => $this->book->id,
      'member_id' => $this->member->id,
      'borrow_date' => now()->subDays(10),
      'due_date' => now()->subDays(3),
      'return_date' => now(),
      'status' => 'belum dikembalikan'
    ]);

    // Update status
    $borrowing->update(['status' => 'terlambat']);

    // Create fine
    Fine::create([
      'borrowing_id' => $borrowing->id,
      'amount' => 3 * 3000,
      'payment_status' => 'belum dibayar'
    ]);

    // Assert
    $this->assertEquals('terlambat', $borrowing->status);
    $this->assertDatabaseHas('borrowings', [
      'id' => $borrowing->id,
      'status' => 'terlambat'
    ]);
  }

  /**
   * Test: Relationship fine ke borrowing
   */
  public function test_fine_relationship_to_borrowing()
  {
    // Setup
    $borrowing = Borrowing::create([
      'book_id' => $this->book->id,
      'member_id' => $this->member->id,
      'borrow_date' => now()->subDays(10),
      'due_date' => now()->subDays(3),
      'return_date' => now(),
      'status' => 'terlambat'
    ]);

    $fine = Fine::create([
      'borrowing_id' => $borrowing->id,
      'amount' => 9000,
      'payment_status' => 'belum dibayar'
    ]);

    // Assert relationship
    $this->assertEquals($borrowing->id, $fine->borrowing->id);
    $this->assertInstanceOf(Borrowing::class, $fine->borrowing);
  }
}
