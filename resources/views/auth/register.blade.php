@extends('layouts.guest')

@section('title', 'Register')

@push('styles')
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700&family=Lato:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
        /* ── Variables ─────────────────────────────── */
        :root {
            --cream: #f4ecd8;
            --cream-light: #fff8e7;
            --brown-dark: #2e1c0e;
            --brown-mid: #4a2e18;
            --brown: #6b4226;
            --brown-light: #8b6b4a;
            --tan: #d2b48c;
            --tan-light: #e8d5b4;
            --red-muted: #7a2020;
            --red-bg: #fdecea;
            --red-border: #f5b8b4;
        }

        /* ── Layout: full viewport, no scroll ─────── */
        html,
        body,
        #layoutAuthentication,
        #layoutAuthentication_content,
        #layoutAuthentication_content>main {
            height: 100%;
            max-height: 100%;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }

        #layoutAuthentication {
            display: flex;
            flex-direction: column;
        }

        #layoutAuthentication_content {
            display: flex;
            flex: 1;
        }

        #layoutAuthentication_content>main {
            display: flex;
            align-items: center;
            justify-content: center;
            flex: 1;
        }

        #layoutAuthentication_footer {
            display: none;
        }

        body {
            background: var(--cream);
            background-image:
                radial-gradient(ellipse at 20% 30%, rgba(139, 107, 74, 0.08) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 70%, rgba(107, 66, 38, 0.06) 0%, transparent 55%);
            font-family: 'Lato', sans-serif;
        }

        /* ── Auth wrapper ──────────────────────────── */
        .auth-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 1.5rem;
        }

        .auth-card {
            width: 520px;
            max-width: calc(100vw - 3rem);
            background: transparent;
            border: none;
            box-shadow: none;
            animation: fadeUp 0.5s ease both;
        }

        .auth-card * {
            box-sizing: border-box;
        }

        @keyframes fadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── Icon badge ────────────────────────────── */
        .icon-badge {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: var(--tan-light);
            border: 1px solid var(--tan);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .icon-badge svg {
            width: 26px;
            height: 26px;
            stroke: var(--brown);
            fill: none;
            stroke-width: 1.8;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* ── Header ────────────────────────────────── */
        .auth-header {
            text-align: center;
            margin-bottom: 1.75rem;
        }

        .auth-title {
            font-family: 'Playfair Display', serif;
            font-size: 26px;
            font-weight: 700;
            color: var(--brown-dark);
            margin-bottom: 0.4rem;
            letter-spacing: -0.3px;
        }

        .auth-subtitle {
            font-size: 13.5px;
            color: var(--brown-light);
            line-height: 1.6;
            font-weight: 400;
            margin: 0;
        }

        /* ── Alert ─────────────────────────────────── */
        .auth-alert {
            background: var(--red-bg);
            border: 1px solid var(--red-border);
            color: var(--red-muted);
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 13px;
            margin-bottom: 1.25rem;
        }

        /* ── Form grid ──────────────────────────────── */
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0 1rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        /* Override Bootstrap label */
        .auth-card .form-label {
            display: block;
            margin-bottom: 6px;
            font-size: 11.5px;
            font-weight: 700;
            color: var(--brown-mid);
            letter-spacing: 0.6px;
            text-transform: uppercase;
        }

        /* Override Bootstrap form-control */
        .auth-card .form-control {
            width: 100%;
            padding: 10px 14px;
            border: 1px solid var(--tan);
            border-radius: 10px;
            background: var(--cream-light);
            color: var(--brown-dark);
            font-size: 13.5px;
            font-family: 'Lato', sans-serif;
            transition: border-color 0.18s, box-shadow 0.18s;
        }

        .auth-card .form-control:focus {
            outline: none;
            border-color: var(--brown);
            box-shadow: 0 0 0 3px rgba(107, 66, 38, 0.12);
            background: var(--cream-light);
            color: var(--brown-dark);
        }

        .auth-card .form-control::placeholder {
            color: var(--brown-light);
            opacity: 0.6;
        }

        .auth-card .form-control.is-invalid {
            border-color: var(--red-muted);
        }

        .invalid-feedback {
            font-size: 11.5px;
            color: var(--red-muted);
            margin-top: 4px;
            display: block;
        }

        /* ── Submit button ──────────────────────────── */
        .btn-submit {
            width: 100%;
            padding: 12px;
            margin-top: 0.5rem;
            background: var(--brown);
            color: #fff;
            border: none;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 600;
            font-family: 'Lato', sans-serif;
            letter-spacing: 0.3px;
            cursor: pointer;
            transition: background 0.2s, transform 0.12s, box-shadow 0.2s;
            box-shadow: 0 2px 8px rgba(107, 66, 38, 0.25);
        }

        .btn-submit:hover {
            background: var(--brown-mid);
            box-shadow: 0 4px 14px rgba(107, 66, 38, 0.35);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* ── Back / login link ──────────────────────── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12.5px;
            color: var(--brown-light);
            text-decoration: none;
            font-weight: 500;
            margin-top: 1rem;
            transition: color 0.15s;
        }

        .back-link:hover {
            color: var(--brown);
            text-decoration: none;
        }

        .back-link svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* ── Responsive ─────────────────────────────── */
        @media (max-width: 540px) {
            .form-row {
                grid-template-columns: 1fr;
            }
        }
    </style>
@endpush

@section('content')
    <div class="auth-container">
        <div class="auth-card">

            {{-- Icon badge --}}
            <div class="icon-badge">
                <svg viewBox="0 0 24 24">
                    <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20" />
                    <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z" />
                </svg>
            </div>

            {{-- Header --}}
            <div class="auth-header">
                <h1 class="auth-title">Buat Akun</h1>
                <p class="auth-subtitle">
                    Daftarkan diri Anda untuk mengakses perpustakaan.
                </p>
            </div>

            {{-- Error alert --}}
            @if ($errors->any())
                <div class="auth-alert">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('register') }}">
                @csrf

                {{-- Row 1: Nama & Email --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            placeholder="Nama lengkap" value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="user@example.com" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Row 2: Password & Konfirmasi --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Kata Sandi</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="••••••••" required>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Konfirmasi Kata Sandi</label>
                        <input type="password" name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="••••••••"
                            required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Row 3: Alamat & Telepon --}}
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">Alamat</label>
                        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror"
                            placeholder="Alamat lengkap" value="{{ old('address') }}" required>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Nomor Telepon</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                            placeholder="+62 xxx xxxx xxxx" value="{{ old('phone') }}" required>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit">Buat Akun</button>

            </form>

            {{-- Login link --}}
            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="back-link">
                    Sudah punya akun? Masuk
                </a>
            </div>

        </div>
    </div>
@endsection
