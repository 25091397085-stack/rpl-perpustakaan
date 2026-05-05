@extends('layouts.app')

@section('title', 'Edit Member')
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4">Member</h1>

        <div class="card mb-4">
            <div class="card-header d-flex align-items-center justify-content-between"
                style="background: #7a5c38; padding: 11px 18px; border: none;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-edit" style="color: #e8d9c0; font-size: 13px;"></i>
                    <p class="mb-0" style="color: #f5ece0; font-size: 13px; font-weight: 500;">Edit Member</p>
                </div>

                <a href="{{ route('members.index') }}"
                    style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:6px;border:1px solid #c9a96e;background:#f0d9b5;color:#4a3018;font-size:12px;font-weight:500;text-decoration:none;"
                    onmouseover="this.style.background='#e8c98a'" onmouseout="this.style.background='#f0d9b5'">Kembali</a>
            </div>

            <div class="card-body">
                <form action="{{ route('members.update', $member->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Member Code -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" value="{{ $member->member_code }}" readonly>
                        <label>Kode Member</label>
                    </div>

                    <div class="row">
                        <!-- Nama -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $member->name) }}" required>
                                <label>Nama</label>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $member->email) }}" required>
                                <label>Email</label>
                            </div>
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="form-floating mb-3">
                        <input type="text" name="address" class="form-control"
                            value="{{ old('address', $member->address) }}" required>
                        <label>Alamat</label>
                    </div>

                    <!-- Phone -->
                    <div class="form-floating mb-3">
                        <input type="text" name="phone" class="form-control" value="{{ old('phone', $member->phone) }}"
                            required>
                        <label>No HP</label>
                    </div>

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
