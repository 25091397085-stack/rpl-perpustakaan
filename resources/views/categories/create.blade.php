@extends('layouts.app')

@section('title', 'Perpustakaan - Kategori Buku')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Kategori</h1>

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between"
                style="background: #7a5c38; padding: 11px 18px; border: none;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-plus" style="color: #e8d9c0; font-size: 13px;"></i>
                    <p class="mb-0" style="color: #f5ece0; font-size: 13px; font-weight: 500;">Tambah Kategori</p>
                </div>

                <a href="{{ route('categories.index') }}"
                    style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:6px;border:1px solid #c9a96e;background:#f0d9b5;color:#4a3018;font-size:12px;font-weight:500;text-decoration:none;"
                    onmouseover="this.style.background='#e8c98a'" onmouseout="this.style.background='#f0d9b5'">Kembali</a>
            </div>

            <div class="card-body">
                <form action="{{ route('categories.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Nama Kategori -->
                        <div class="col-md-15">
                            <div class="d-flex align-items-center gap-2 mb-3">
                                <div class="form-floating flex-grow-1">
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Nama Kategori">
                                    <label for="name">Nama Kategori</label>
                                </div>
                                <button type="submit" class="btn btn-primary py-3">
                                    <!-- py-3 ditambahkan agar tinggi tombol seimbang dengan form-floating -->
                                    <i class="fas fa-save me-1"></i> Simpan
                                </button>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
@endsection
