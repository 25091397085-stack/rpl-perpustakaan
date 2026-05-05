<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FineController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('member.home');
});

Route::get('tabel', function () {
    return view('dashboard.table');
})->name('table');

Route::middleware(['auth'])->group(function () {

    // ADMIN ONLY
    Route::middleware('role:admin')->group(function () {
        Route::resource('members', MemberController::class);
        Route::resource('categories', CategoryController::class);
        Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'returnBook'])
            ->name('borrowings.return');

        Route::patch('/borrowings/{borrowing}/late', [BorrowingController::class, 'markLate'])
            ->name('borrowings.late');
        Route::resource('books', BookController::class);
    });

    // ADMIN + MEMBER
    // Route::middleware('role:admin|member')->group(function () {
        Route::resource('borrowings', BorrowingController::class)->only(['index']);
        Route::get('/home', [BorrowingController::class, 'home'])->name('member.home');
        Route::post('/borrow/{book}', [BorrowingController::class, 'borrow'])->name('borrow.book');
        Route::resource('fines', FineController::class);
        Route::patch('/fines/{fine}/pay', [FineController::class, 'pay'])->name('fines.pay');
        Route::post('/fines/late', [FineController::class, 'late'])->name('fines.late');
    // });

    // PROFILE (semua login)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Testing Routes (Development Only - hapus setelah production)
require __DIR__ . '/test.php';

require __DIR__ . '/auth.php';
