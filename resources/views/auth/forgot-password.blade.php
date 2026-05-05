@extends('layouts.guest')

@section('title', 'Atur Ulang Kata Sandi')

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
            --green-muted: #2e5c2e;
            --green-bg: rgba(154, 196, 121, 0.16);
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

        .auth-alert-success {
            background: var(--green-bg);
            border: 1px solid rgba(46, 92, 46, 0.3);
            color: var(--green-muted);
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
            transition: background 0.2s;
        }

        .btn-submit:hover {
            background: var(--brown-light);
        }

        /* ── Back link ──────────────────────────── */
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
                        d="M12 1a5 5 0 0 1 5 5v4h2a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2V6a5 5 0 0 1 5-5z" />
                </svg>
            </div>

            {{-- Header --}}
            <div class="auth-header">
                <h1 class="auth-title">Atur Ulang Kata Sandi</h1>
                <p class="auth-subtitle">
                    Masukkan alamat email Anda dan kami akan mengirimkan tautan untuk mengatur ulang kata sandi.
                </p>
            </div>

            {{-- Success message --}}
            @if (session('status'))
                <div class="auth-alert-success">
                    {{ session('status') }}
                </div>
            @endif

            {{-- Error alert --}}
            @if ($errors->any())
                <div class="auth-alert">
                    {{ $errors->first() }}
                </div>
            @endif

            {{-- Form --}}
            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label for="email" class="form-label">Alamat Email</label>
                    <input id="email" name="email" type="email" class="form-control" placeholder="user@example.com"
                        value="{{ old('email') }}" required autofocus>
                </div>

                {{-- Submit --}}
                <button type="submit" class="btn-submit">Kirim Tautan Reset</button>

            </form>

            {{-- Back link --}}
            <div style="text-align: center;">
                <a href="{{ route('login') }}" class="back-link">
                    Kembali ke Masuk
                </a>
            </div>

        </div>
    </div>
@endsection
