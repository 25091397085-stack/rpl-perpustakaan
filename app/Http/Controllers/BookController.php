<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $books = Book::with('category')->get();
        // return response()->json($books);
        return view('books.index', compact('books'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = Category::all();
        return view('books.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cover' => 'required|image|mimes:jpg,jpeg,png|max:2048',
            'stock' => 'required|integer|min:0',
            'sinopsis' => 'required|string'
        ]);

        try {
            // upload cover
            $coverPath = $request->file('cover')->store('covers', 'public');

            // simpan data
            Book::create([
                'title' => $request->title,
                'author' => $request->author,
                'category_id' => $request->category_id,
                'cover' => $coverPath,
                'stock' => $request->stock,
                'sinopsis' => $request->sinopsis,
            ]);

            return redirect()->route('books.index')
                ->with('success', 'Buku berhasil ditambahkan');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal upload buku');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Book $book)
    {
        $categories = Category::all();
        return view('books.edit', compact('book', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'cover' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'stock' => 'required|integer|min:0',
            'sinopsis' => 'required|string'
        ]);

        try {
            $coverPath = $book->cover;

            // jika upload cover baru
            if ($request->hasFile('cover')) {

                // hapus cover lama
                if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                    Storage::disk('public')->delete($book->cover);
                }

                // upload baru
                $coverPath = $request->file('cover')->store('covers', 'public');
            }

            // update data
            $book->update([
                'title' => $request->title,
                'author' => $request->author,
                'category_id' => $request->category_id,
                'cover' => $coverPath,
                'stock' => $request->stock,
                'sinopsis' => $request->sinopsis,
            ]);

            return redirect()->route('books.index')
                ->with('success', 'Buku berhasil diupdate');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal update buku');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        try {
            // Hapus cover jika ada
            if ($book->cover && Storage::disk('public')->exists($book->cover)) {
                Storage::disk('public')->delete($book->cover);
            }

            // Hapus data buku
            $book->delete($book->id);

            return redirect()->route('books.index')
                ->with('success', 'Buku berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus buku');
        }
    }
}
