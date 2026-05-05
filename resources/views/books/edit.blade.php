@extends('layouts.app')

@section('title', 'Edit Buku')

@section('content')
    <style>
        .two-col {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        @media (max-width: 576px) {
            .two-col {
                grid-template-columns: 1fr;
            }

            .cover-row {
                flex-direction: column;
            }

            .cover-thumb {
                width: 100%;
                height: 160px;
            }
        }

        .card-header-custom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.6rem 1rem;
            background-color: #6b5230;
            color: #fff;
            font-size: 0.82rem;
        }

        .card-header-custom .btn-back {
            font-size: 0.78rem;
            padding: 0.25rem 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.6);
            color: #fff;
            background: transparent;
            border-radius: 4px;
            text-decoration: none;
            transition: background 0.15s;
        }

        .card-header-custom .btn-back:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .cover-row {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            padding: 1rem;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }

        .cover-thumb {
            width: 70px;
            height: 95px;
            object-fit: cover;
            border-radius: 4px;
            border: 1px solid #dee2e6;
            flex-shrink: 0;
        }

        .cover-info {
            flex: 1;
        }

        .cover-info small {
            color: #6c757d;
        }
    </style>

    <div class="container-fluid px-4">
        <h1 class="mt-4">Buku</h1>

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between"
                style="background: #7a5c38; padding: 11px 18px; border: none;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-edit" style="color: #e8d9c0; font-size: 13px;"></i>
                    <p class="mb-0" style="color: #f5ece0; font-size: 13px; font-weight: 500;">Edit Buku</p>
                </div>

                <a href="{{ route('books.index') }}"
                    style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:6px;border:1px solid #c9a96e;background:#f0d9b5;color:#4a3018;font-size:12px;font-weight:500;text-decoration:none;"
                    onmouseover="this.style.background='#e8c98a'" onmouseout="this.style.background='#f0d9b5'">Kembali</a>
            </div>

            <div class="card-body">
                <form action="{{ route('books.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Judul & Author --}}
                    <div class="two-col mb-3">
                        <div class="form-floating">
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                placeholder="Judul Buku" value="{{ old('title', $book->title) }}" required>
                            <label>Judul Buku</label>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-floating">
                            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror"
                                placeholder="Penulis" value="{{ old('author', $book->author) }}" required>
                            <label>Penulis</label>
                            @error('author')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="form-floating mb-3">
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $book->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <label>Kategori</label>
                        @error('category_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Cover --}}
                    <div class="mb-3">
                        <label class="form-label">Cover Buku</label>
                        <div class="cover-row">
                            <img src="{{ asset('storage/' . $book->cover) }}" alt="Cover {{ $book->title }}"
                                class="cover-thumb">
                            <div class="cover-info">
                                <div class="mb-1"><strong style="font-size:0.85rem;">Cover saat ini</strong></div>
                                <small>Unggah file baru untuk mengganti cover (opsional)</small>
                                <input type="file" name="cover" accept="image/*"
                                    class="form-control form-control-sm mt-2">
                                @error('cover')
                                    <small class="text-danger d-block mt-1">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Stock --}}
                    <div class="form-floating mb-3">
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                            placeholder="Stok" value="{{ old('stock', $book->stock) }}" required>
                        <label>Stok</label>
                        @error('stock')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Sinopsis --}}
                    <div class="form-floating mb-3">
                        <textarea name="sinopsis" class="form-control @error('sinopsis') is-invalid @enderror" style="height: 120px;"
                            placeholder="Sinopsis">{{ old('sinopsis', $book->sinopsis) }}</textarea>
                        <label>Sinopsis</label>
                        @error('sinopsis')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Button --}}
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Update
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
