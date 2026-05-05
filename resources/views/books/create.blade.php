@extends('layouts.app')

@section('title', 'Perpustakaan - Tambah Buku')

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
            border-color: rgba(255, 255, 255, 0.6);
            color: #fff;
            background: transparent;
            border-radius: 4px;
            border: 1px solid rgba(255, 255, 255, 0.6);
            text-decoration: none;
            transition: background 0.15s;
        }

        .card-header-custom .btn-back:hover {
            background: rgba(255, 255, 255, 0.15);
        }

        .file-upload-area {
            border: 1.5px dashed #dee2e6;
            border-radius: 6px;
            padding: 1.25rem 1rem;
            text-align: center;
            background: transparent;
            cursor: pointer;
            transition: border-color 0.15s;
            position: relative;
        }

        .file-upload-area:hover {
            border-color: #86b7fe;
        }

        .file-upload-area input[type="file"] {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
        }

        .file-upload-area .upload-icon {
            font-size: 1.3rem;
            color: #adb5bd;
            margin-bottom: 0.3rem;
        }

        .file-upload-area .upload-text {
            font-size: 0.82rem;
            color: #6c757d;
        }

        .file-upload-area .upload-hint {
            font-size: 0.73rem;
            color: #adb5bd;
            margin-top: 2px;
        }
    </style>

    <div class="container-fluid px-4">
        <h1 class="mt-4">Buku</h1>

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between"
                style="background: #7a5c38; padding: 11px 18px; border: none;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-plus" style="color: #e8d9c0; font-size: 13px;"></i>
                    <p class="mb-0" style="color: #f5ece0; font-size: 13px; font-weight: 500;">Tambah Buku</p>
                </div>

                <a href="{{ route('books.index') }}"
                    style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:6px;border:1px solid #c9a96e;background:#f0d9b5;color:#4a3018;font-size:12px;font-weight:500;text-decoration:none;"
                    onmouseover="this.style.background='#e8c98a'"
                    onmouseout="this.style.background='#f0d9b5'"class="btn-back">Kembali</a>
            </div>

            <div class="card-body">
                <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Judul & Author --}}
                    <div class="two-col mb-3">
                        <div class="form-floating">
                            <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                                placeholder="Judul Buku" value="{{ old('title') }}" required>
                            <label>Judul Buku</label>
                            @error('title')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-floating">
                            <input type="text" name="author" class="form-control @error('author') is-invalid @enderror"
                                placeholder="Penulis" value="{{ old('author') }}" required>
                            <label>Penulis</label>
                            @error('author')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- Kategori --}}
                    <div class="form-floating mb-3">
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <label>Kategori</label>
                        @error('category_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Cover Upload --}}
                    <div class="mb-3">
                        <label class="form-label">Cover Buku</label>
                        <div class="file-upload-area">
                            <input type="file" name="cover" accept="image/*" required>
                            <div class="upload-icon"><i class="fas fa-image"></i></div>
                            <div class="upload-text">Klik atau seret file ke sini</div>
                            <div class="upload-hint">PNG, JPG, WEBP — maks. 2MB</div>
                        </div>
                        @error('cover')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Stock --}}
                    <div class="form-floating mb-3">
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror"
                            placeholder="Stok" value="{{ old('stock') }}" required>
                        <label>Stok</label>
                        @error('stock')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Sinopsis --}}
                    <div class="form-floating mb-3">
                        <textarea name="sinopsis" class="form-control @error('sinopsis') is-invalid @enderror" style="height: 120px;"
                            placeholder="Sinopsis">{{ old('sinopsis') }}</textarea>
                        <label>Sinopsis</label>
                        @error('sinopsis')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Button --}}
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-save"></i> Simpan
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
