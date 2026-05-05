@extends('layouts.guest')

@section('title', 'Verify Email')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-card-header">
                <h1 class="auth-title">✉️ Verify Your Email</h1>
                <p class="auth-subtitle">
                    We've sent a verification link to your email address. Please check your inbox and click the link to
                    continue.
                </p>
            </div>

            <div class="auth-card-body">
                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success">
                        ✓ Verification link sent successfully! Check your email.
                    </div>
                @endif

                <p class="text-muted" style="font-size: 14px; margin-bottom: 28px;">
                    Didn't receive the email? Click the button below to resend it.
                </p>

                <form method="POST" action="{{ route('verification.send') }}" style="margin-bottom: 16px;">
                    @csrf
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        Resend Verification Email
                    </button>
                </form>
            </div>

            <div class="auth-card-footer">
                <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                    @csrf
                    <button type="submit" class="auth-footer-link"
                        style="background: none; border: none; cursor: pointer; padding: 0;">
                        Sign out
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
