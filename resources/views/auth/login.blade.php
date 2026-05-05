@extends('layouts.guest')

@section('title', 'Login')

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
            width: 380px;
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

        /* ── Form ───────────────────────────────────── */
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

        /* Label row — label kiri, link kanan */
        .label-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 6px;
        }

        .label-row .form-label {
            margin-bottom: 0;
        }

        /* Override Bootstrap form-control */
        .auth-card .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid var(--tan);
            border-radius: 10px;
            background: var(--cream-light);
            color: var(--brown-dark);
            font-size: 14px;
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

        /* ── Forgot password link ───────────────────── */
        .link-muted {
            font-size: 12px;
            color: var(--brown-light);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s;
            white-space: nowrap;
        }

        .link-muted:hover {
            color: var(--brown);
            text-decoration: none;
        }

        /* ── Submit button ──────────────────────────── */
        .btn-submit {
            width: 100%;
            padding: 12px;
            margin-top: 1.25rem;
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

        /* ── Register link ──────────────────────────── */
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12.5px;
            color: var(--brown-light);
            text-decoration: none;
            font-weight: 500;
            margin-top: 1.25rem;
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
    </style>
@endpush

@section('content')
    <div class="auth-container">
        <div class="auth-card">

            {{-- Icon badge --}}
            <div class="icon-badge">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M21 2l-2 2m-7.61 7.61a5.5 5.5 0 1 1-7.778 7.778 5.5 5.5 0 0 1 7.777-7.777zm0 0L15.5 7.5m0 0l3 3L22 7l-3-3m-3.5 3.5L19 4" />
                </svg>
            </div>

            {{-- Header --}}
            <div class="auth-header">
                <h1 class="auth-title">Masuk</h1>
                <p class="auth-subtitle">
                    Selamat datang kembali.<br>
                    Masukkan data akun Anda untuk melanjutkan.
                </p>
            </div>

            {{-- Error alert --}}
            @if ($errors->any())
                <div class="auth-alert">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label for="email" class="form-label">Email</label>
                    <input id="email" name="email" type="email" class="form-control" placeholder="user@example.com"
                        value="{{ old('email') }}" required autofocus>
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <div class="label-row">
                        <label for="password" class="form-label">Kata Sandi</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="link-muted">Lupa kata sandi?</a>
                        @endif
                    </div>
                    <input id="password" name="password" type="password" class="form-control" placeholder="••••••••"
                        required>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit">Masuk</button>

            </form>

            {{-- Register link --}}
            <div style="text-align: center;">
                <a href="{{ route('register') }}" class="back-link">
                    Belum punya akun? Daftar
                </a>
            </div>

        </div>
    </div>
@endsection
