@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="container-fluid px-4">

        <div class="profile-header mb-4">
            <h1 class="mt-4 mb-3">Profil</h1>
        </div>

        <div class="row g-4">

            {{-- ======================================================= --}}
            {{-- KOLOM KIRI: Update Profile (+ Data Member jika member)  --}}
            {{-- ======================================================= --}}
            <div class="col-lg-6">
                <div class="profile-card">
                    <div class="profile-card-header">
                        <div class="header-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <div class="header-title">Informasi Akun</div>
                            <div class="header-sub">Nama, email
                                @role('member')
                                    , alamat & nomor HP
                                @endrole
                            </div>
                        </div>
                    </div>

                    <div class="profile-card-body">
                        {{-- Form dari partials update-profile --}}
                        @include('profile.partials.update-profile-information-form')

                        {{-- Data Member (khusus role member, disatukan) --}}
                        @role('member')
                            <div class="member-divider">
                                <span>Data Member</span>
                            </div>

                            <form method="POST" action="{{ route('profile.update') }}">
                                @csrf
                                @method('PATCH')

                                <div class="field-group mb-3">
                                    <label class="field-label">Alamat</label>
                                    <input type="text" name="address" class="field-input"
                                        placeholder="Masukkan alamat lengkap"
                                        value="{{ old('address', auth()->user()->member->address ?? '') }}" required>
                                </div>

                                <div class="field-group mb-4">
                                    <label class="field-label">No. HP</label>
                                    <input type="text" name="phone" class="field-input" placeholder="Contoh: 08123456789"
                                        value="{{ old('phone', auth()->user()->member->phone ?? '') }}" required>
                                </div>

                                <div class="d-flex">
                                    <button type="submit" class="btn-primary-warm">
                                        <i class="fas fa-save me-2"></i> Simpan
                                    </button>
                                </div>
                            </form>
                        @endrole
                    </div>
                </div>
            </div>

            {{-- ======================================================= --}}
            {{-- KOLOM KANAN: Update Password + Delete Account           --}}
            {{-- ======================================================= --}}
            <div class="col-lg-6">

                {{-- Update Password --}}
                <div class="profile-card mb-4">
                    <div class="profile-card-header">
                        <div class="header-icon">
                            <i class="fas fa-lock"></i>
                        </div>
                        <div>
                            <div class="header-title">Ubah Password</div>
                            <div class="header-sub">Perbarui kata sandi akun Anda</div>
                        </div>
                    </div>
                    <div class="profile-card-body">
                        @include('profile.partials.update-password-form')
                    </div>
                </div>

                {{-- Delete Account --}}
                <div class="profile-card profile-card--danger">
                    <div class="profile-card-header">
                        <div class="header-icon header-icon--danger">
                            <i class="fas fa-trash-alt"></i>
                        </div>
                        <div>
                            <div class="header-title header-title--danger">Hapus Akun</div>
                            <div class="header-sub">Tindakan ini tidak dapat dibatalkan</div>
                        </div>
                    </div>
                    <div class="profile-card-body">
                        @include('profile.partials.delete-user-form')
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* ── Base Variables ───────────────────────────── */
        :root {
            --warm-900: #2c2416;
            --warm-800: #4a3018;
            --warm-700: #7a5c38;
            --warm-600: #8c7055;
            --warm-500: #a68a6a;
            --warm-400: #c9a96e;
            --warm-300: #d6caad;
            --warm-200: #e8d9c0;
            --warm-100: #f0e6d3;
            --warm-50: #faf5ee;
            --danger: #c0392b;
            --danger-bg: #fff5f5;
            --danger-border: #f5c6c6;
        }

        /* ── Page Header ──────────────────────────────── */
        .profile-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--warm-900);
            margin: 0;
            letter-spacing: -0.3px;
        }

        .profile-subtitle {
            font-size: 13px;
            color: var(--warm-500);
            margin: 2px 0 0;
        }

        /* ── Card ─────────────────────────────────────── */
        .profile-card {
            background: #fff;
            border: 1px solid var(--warm-300);
            border-radius: 12px;
            overflow: hidden;
        }

        .profile-card--danger {
            border-color: var(--danger-border);
        }

        .profile-card-header {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 20px;
            background: var(--warm-700);
            border-bottom: none;
        }

        .profile-card--danger .profile-card-header {
            background: var(--danger-bg);
            border-bottom: 1px solid var(--danger-border);
        }

        .header-icon {
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.15);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--warm-200);
            font-size: 13px;
            flex-shrink: 0;
        }

        .header-icon--danger {
            background: rgba(192, 57, 43, 0.1);
            color: var(--danger);
        }

        .header-title {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            line-height: 1.3;
        }

        .header-title--danger {
            color: var(--danger);
        }

        .header-sub {
            font-size: 11.5px;
            color: var(--warm-200);
            margin-top: 1px;
        }

        .profile-card--danger .header-sub {
            color: var(--warm-500);
        }

        .profile-card-body {
            padding: 22px 20px;
        }

        /* ── Member Divider ───────────────────────────── */
        .member-divider {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 22px 0 18px;
            color: var(--warm-600);
            font-size: 11.5px;
            font-weight: 600;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .member-divider::before,
        .member-divider::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--warm-300);
        }

        /* ── Form Fields ──────────────────────────────── */
        .field-group {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .field-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--warm-700);
            margin: 0;
        }

        .field-input {
            border: 1px solid var(--warm-300);
            border-radius: 8px;
            padding: 9px 13px;
            font-size: 13.5px;
            color: var(--warm-900);
            background: var(--warm-50);
            transition: border-color 0.2s, box-shadow 0.2s;
            width: 100%;
            outline: none;
        }

        .field-input:focus {
            border-color: var(--warm-500);
            box-shadow: 0 0 0 3px rgba(166, 138, 106, 0.12);
            background: #fff;
        }

        .field-input::placeholder {
            color: var(--warm-400);
        }

        /* ── Override Bootstrap form-control inside partials ── */
        .profile-card-body .form-control,
        .profile-card-body .form-floating .form-control {
            border: 1px solid var(--warm-300) !important;
            border-radius: 8px !important;
            background: var(--warm-50) !important;
            color: var(--warm-900) !important;
            font-size: 13.5px !important;
        }

        .profile-card-body .form-control:focus {
            border-color: var(--warm-500) !important;
            box-shadow: 0 0 0 3px rgba(166, 138, 106, 0.12) !important;
            background: #fff !important;
        }

        .profile-card-body label {
            color: var(--warm-600) !important;
            font-size: 12px !important;
            font-weight: 600 !important;
        }

        .profile-card-body .form-floating>label {
            font-size: 13px !important;
        }

        /* ── Buttons ──────────────────────────────────── */
        .btn-primary-warm {
            display: inline-flex;
            align-items: center;
            padding: 8px 18px;
            border-radius: 8px;
            border: 1px solid var(--warm-400);
            background: var(--warm-700);
            color: var(--warm-100);
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: background 0.2s;
        }

        .btn-primary-warm:hover {
            background: var(--warm-800);
            color: #fff;
        }

        /* Override Bootstrap btn inside partials */
        .profile-card-body .btn-primary {
            background: var(--warm-700) !important;
            border-color: var(--warm-400) !important;
            color: var(--warm-100) !important;
            font-size: 13px !important;
            border-radius: 8px !important;
            padding: 8px 18px !important;
        }

        .profile-card-body .btn-primary:hover {
            background: var(--warm-800) !important;
        }

        .profile-card-body .btn-danger {
            background: transparent !important;
            border: 1.5px solid var(--danger) !important;
            color: var(--danger) !important;
            font-size: 13px !important;
            border-radius: 8px !important;
            padding: 8px 18px !important;
        }

        .profile-card-body .btn-danger:hover {
            background: var(--danger) !important;
            color: #fff !important;
        }

        .profile-card-body .btn-secondary {
            background: transparent !important;
            border: 1.5px solid var(--warm-300) !important;
            color: var(--warm-600) !important;
            font-size: 13px !important;
            border-radius: 8px !important;
            padding: 8px 18px !important;
        }

        .profile-card-body .btn-secondary:hover {
            background: var(--warm-100) !important;
        }
    </style>
@endsection
