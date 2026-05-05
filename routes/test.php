<?php

use Illuminate\Support\Facades\Route;
use App\Models\Borrowing;
use App\Models\Fine;
use Carbon\Carbon;

/**
 * Testing Routes untuk Late Borrowing
 * 
 * Endpoint-endpoint ini untuk testing saja, 
 * hapus setelah development selesai!
 */

Route::prefix('test')->middleware('auth')->group(function () {

  /**
   * GET /test/late-borrowing/create-late-data
   * Membuat data borrowing terlambat untuk testing
   */
  Route::get('/late-borrowing/create-late-data', function () {
    $borrowing = Borrowing::create([
      'book_id' => 1,
      'member_id' => 1,
      'borrow_date' => now()->subDays(10),
      'due_date' => now()->subDays(5),      // 5 hari lalu
      'return_date' => null,
      'status' => 'belum dikembalikan'
    ]);

    return response()->json([
      'message' => '✅ Borrowing terlambat 5 hari berhasil dibuat',
      'borrowing' => $borrowing,
      'note' => 'Kunjungi /test/late-borrowing/' . $borrowing->id . '/check-and-fine untuk membuat fine'
    ], 201);
  })->name('test.create-late');

  /**
   * GET /test/late-borrowing/{borrowing}/check-and-fine
   * Check apakah borrowing terlambat dan buat fine
   */
  Route::get('/late-borrowing/{borrowing}/check-and-fine', function (Borrowing $borrowing) {
    $returnDate = now()->toDateString();
    $dueDate = $borrowing->due_date;
    $isLate = Carbon::parse($returnDate)->gt(Carbon::parse($dueDate));
    $lateDays = 0;
    $fineAmount = 0;
    $fine = null;

    if ($isLate) {
      $lateDays = Carbon::parse($dueDate)->diffInDays(Carbon::parse($returnDate));
      $fineAmount = $lateDays * 3000;

      // Cek apakah sudah ada fine
      $existingFine = Fine::where('borrowing_id', $borrowing->id)->first();
      if ($existingFine) {
        return response()->json([
          'message' => '⚠️ Fine sudah ada',
          'borrowing' => $borrowing,
          'fine' => $existingFine
        ], 400);
      }

      // Buat fine
      $fine = Fine::create([
        'borrowing_id' => $borrowing->id,
        'amount' => $fineAmount,
        'payment_status' => 'belum dibayar'
      ]);

      // Update borrowing status
      $borrowing->update(['status' => 'terlambat']);
    }

    return response()->json([
      'message' => $isLate ? '✅ Borrowing terlambat, fine telah dibuat' : '✅ Borrowing tidak terlambat',
      'borrowing' => $borrowing,
      'is_late' => $isLate,
      'late_days' => $lateDays,
      'fine_amount' => $fineAmount,
      'fine' => $fine
    ], 201);
  })->name('test.check-fine');

  /**
   * GET /test/late-borrowing/list-all
   * List semua borrowing dengan status dan fine
   */
  Route::get('/late-borrowing/list-all', function () {
    $borrowings = Borrowing::with('fine', 'book', 'member')
      ->get()
      ->map(function ($b) {
        $isLate = Carbon::parse($b->due_date)->isPast();
        return [
          'id' => $b->id,
          'book' => $b->book->name,
          'member' => $b->member->user->name,
          'borrow_date' => $b->borrow_date,
          'due_date' => $b->due_date,
          'return_date' => $b->return_date,
          'status' => $b->status,
          'is_late' => $isLate,
          'days_late' => $isLate ? Carbon::parse($b->due_date)->diffInDays(now()) : 0,
          'fine' => $b->fine
        ];
      });

    return response()->json($borrowings, 200);
  })->name('test.list-borrowings');

  /**
   * GET /test/fines/list-all
   * List semua fine
   */
  Route::get('/fines/list-all', function () {
    $fines = Fine::with('borrowing.book', 'borrowing.member.user')
      ->get()
      ->map(function ($f) {
        return [
          'id' => $f->id,
          'borrowing_id' => $f->borrowing_id,
          'book' => $f->borrowing->book->name,
          'member' => $f->borrowing->member->user->name,
          'amount' => 'Rp. ' . number_format($f->amount),
          'payment_status' => $f->payment_status,
          'created_at' => $f->created_at
        ];
      });

    return response()->json($fines, 200);
  })->name('test.list-fines');

  /**
   * GET /test/late-borrowing/batch-check
   * Batch check semua borrowing dan auto create fine untuk yang terlambat
   */
  Route::get('/late-borrowing/batch-check', function () {
    $overdueBorrowings = Borrowing::where('status', 'belum dikembalikan')
      ->where('due_date', '<', now()->toDateString())
      ->get();

    $processed = [];
    $skipped = [];

    foreach ($overdueBorrowings as $borrowing) {
      $existingFine = Fine::where('borrowing_id', $borrowing->id)->first();

      if ($existingFine) {
        $skipped[] = [
          'borrowing_id' => $borrowing->id,
          'reason' => 'Fine sudah ada'
        ];
      } else {
        $lateDays = Carbon::parse($borrowing->due_date)->diffInDays(now());
        $fineAmount = $lateDays * 3000;

        $fine = Fine::create([
          'borrowing_id' => $borrowing->id,
          'amount' => $fineAmount,
          'payment_status' => 'belum dibayar'
        ]);

        $borrowing->update(['status' => 'terlambat']);

        $processed[] = [
          'borrowing_id' => $borrowing->id,
          'late_days' => $lateDays,
          'fine_amount' => 'Rp. ' . number_format($fineAmount),
          'fine_id' => $fine->id
        ];
      }
    }

    return response()->json([
      'message' => '✅ Batch check selesai',
      'processed' => count($processed),
      'skipped' => count($skipped),
      'details' => [
        'processed' => $processed,
        'skipped' => $skipped
      ]
    ], 200);
  })->name('test.batch-check');

  /**
   * GET /test/reset
   * Reset semua test data
   */
  Route::get('/reset', function () {
    Fine::truncate();
    Borrowing::truncate();

    return response()->json([
      'message' => '✅ Test data berhasil direset'
    ], 200);
  })->name('test.reset');
});
