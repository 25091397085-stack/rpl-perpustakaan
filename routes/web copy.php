<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('categories.welcome');
});

Route::get('dashboard', function(){
    return view('dashboard.index');
})->name('dashboard');

Route::get('tabel', function(){
    return view('dashboard.table');
})->name('table');

Route::resource('categories', CategoryController::class);
Route::resource('books', BookController::class);
Route::resource('members', MemberController::class);
