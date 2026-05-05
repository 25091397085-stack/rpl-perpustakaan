<?php

use App\Models\User;
use App\Models\Book;
use App\Models\Category;
use Spatie\Permission\Models\Role;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    $this->adminRole = Role::firstOrCreate(['name' => 'admin']);
    $this->memberRole = Role::firstOrCreate(['name' => 'member']);
    
    $this->admin = User::factory()->create();
    $this->admin->assignRole($this->adminRole);
    
    $this->member = User::factory()->create();
    $this->member->assignRole($this->memberRole);

    $this->category = Category::firstOrCreate(['name' => 'Novel']);
});

test('admin can create book', function () {
    Storage::fake('public');
    
    $response = $this->actingAs($this->admin)->post('/books', [
        'category_id' => $this->category->id,
        'title' => 'Harry Potter',
        'author' => 'J.K. Rowling',
        'stock' => 5,
        'sinopsis' => 'A magic book',
        'cover' => UploadedFile::fake()->image('cover.jpg')
    ]);
    
    $response->assertRedirect('/books');
    $this->assertDatabaseHas('books', ['title' => 'Harry Potter']);
});

test('admin can update book stock', function () {
    Storage::fake('public');

    $book = Book::create([
        'category_id' => $this->category->id,
        'title' => 'Original',
        'author' => 'Author',
        'cover' => 'old_cover.jpg',
        'stock' => 5,
        'sinopsis' => 'Sinopsis',
    ]);
    
    $response = $this->actingAs($this->admin)->put("/books/{$book->id}", [
        'category_id' => $this->category->id,
        'title' => 'Original Updated',
        'author' => 'Author',
        'stock' => 10,
        'sinopsis' => 'Sinopsis',
        'cover' => UploadedFile::fake()->image('new_cover.jpg')
    ]);
    
    $response->assertRedirect('/books');
    $this->assertDatabaseHas('books', ['id' => $book->id, 'stock' => 10, 'title' => 'Original Updated']);
});

