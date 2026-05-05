@extends('layouts.app')

@section('title', 'Tambah Member')
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Member</h1>

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between"
                style="background: #7a5c38; padding: 11px 18px; border: none;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-plus" style="color: #e8d9c0; font-size: 13px;"></i>
                    <p class="mb-0" style="color: #f5ece0; font-size: 13px; font-weight: 500;">Tambah Member</p>
                </div>

                <a href="{{ route('members.index') }}"
                    style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:6px;border:1px solid #c9a96e;background:#f0d9b5;color:#4a3018;font-size:12px;font-weight:500;text-decoration:none;"
                    onmouseover="this.style.background='#e8c98a'"
                    onmouseout="this.style.background='#f0d9b5'"class="btn-back">Kembali</a>
            </div>

            <div class="card-body">
                <form action="{{ route('members.store') }}" method="POST">
                    @csrf

                    <div class="row">
                        <!-- Nama -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="name" class="form-control"
                                placeholder="Masukan Nama" required>
                                <label>Nama</label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="email" name="email" class="form-control"
                                placeholder="Masukan Email" required>
                                <label>Email</label>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="form-floating mb-3">
                        <input type="text" name="address" class="form-control"
                        placeholder="Masukan Alamat"required>
                        <label>Alamat</label>
                    </div>

                    <!-- Phone -->
                    <div class="form-floating mb-3">
                        <input type="text" name="phone" class="form-control"
                        placeholder="Masukan No HP"required>
                        <label>No HP</label>
                    </div>

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
