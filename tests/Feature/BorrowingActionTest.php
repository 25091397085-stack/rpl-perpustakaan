<?php

use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use App\Models\Member;
use App\Models\Borrowing;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    $this->adminRole = Role::firstOrCreate(['name' => 'admin']);
    $this->memberRole = Role::firstOrCreate(['name' => 'member']);
    
    $this->admin = User::factory()->create();
    $this->admin->assignRole($this->adminRole);
    
    $this->user = User::factory()->create();
    $this->user->assignRole($this->memberRole);

    $this->member = Member::create([
        'user_id' => $this->user->id,
        'member_code' => 12345,
        'name' => 'Test Member',
        'email' => 'test@member.com',
        'address' => 'Addr',
        'phone' => '08111222333'
    ]);

    $this->category = Category::firstOrCreate(['name' => 'Novel']);
    
    $this->book = Book::create([
        'category_id' => $this->category->id,
        'title' => 'Test Book',
        'author' => 'Author',
        'cover' => 'cover.jpg',
        'stock' => 5,
        'sinopsis' => 'Sinopsis',
    ]);
});

test('member can borrow a book and stock decreases', function () {
    $response = $this->actingAs($this->user)->post("/borrow/{$this->book->id}");
    
    $response->assertRedirect();
    $response->assertSessionHas('success');
    
    $this->assertDatabaseHas('borrowings', [
        'member_id' => $this->member->id,
        'book_id' => $this->book->id,
        'status' => 'belum dikembalikan'
    ]);
    
    $this->assertDatabaseHas('books', [
        'id' => $this->book->id,
        'stock' => 4 // 5 - 1
    ]);
});

test('member cannot borrow out of stock book', function () {
    $this->book->update(['stock' => 0]);
    
    $response = $this->actingAs($this->user)->post("/borrow/{$this->book->id}");
    
    $response->assertSessionHas('error');
    
    $this->assertDatabaseMissing('borrowings', [
        'member_id' => $this->member->id,
        'book_id' => $this->book->id,
    ]);
});

test('member cannot borrow more than 3 books', function () {
    // Pinjam 3 buku pertama
    Borrowing::create(['book_id' => $this->book->id, 'member_id' => $this->member->id, 'borrow_date' => now(), 'due_date' => now()->addDays(7), 'status' => 'belum dikembalikan']);
    Borrowing::create(['book_id' => $this->book->id, 'member_id' => $this->member->id, 'borrow_date' => now(), 'due_date' => now()->addDays(7), 'status' => 'belum dikembalikan']);
    Borrowing::create(['book_id' => $this->book->id, 'member_id' => $this->member->id, 'borrow_date' => now(), 'due_date' => now()->addDays(7), 'status' => 'belum dikembalikan']);
    
    // Coba pinjam buku ke-4
    $book2 = Book::create(['category_id' => $this->category->id, 'title' => 'Book 4', 'author' => 'A', 'cover' => 'x.jpg', 'stock' => 5, 'sinopsis' => 'S']);
    $response = $this->actingAs($this->user)->post("/borrow/{$book2->id}");
    
    $response->assertSessionHas('error'); // Ditolak
});

test('admin can return book and stock increases', function () {
    $borrowing = Borrowing::create([
        'book_id' => $this->book->id,
        'member_id' => $this->member->id,
        'borrow_date' => now(),
        'due_date' => now()->addDays(7),
        'status' => 'belum dikembalikan'
    ]);
    
    // Admin melakukan pengembalian
    $response = $this->actingAs($this->admin)->patch("/borrowings/{$borrowing->id}/return");
    
    $response->assertRedirect();
    
    $this->assertDatabaseHas('borrowings', [
        'id' => $borrowing->id,
        'status' => 'sudah dikembalikan'
    ]);
    
    $this->assertDatabaseHas('books', [
        'id' => $this->book->id,
        'stock' => 6 // 5 + 1
    ]);
});
