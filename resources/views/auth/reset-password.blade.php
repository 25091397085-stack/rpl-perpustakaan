@extends('layouts.guest')

@section('title', 'Reset Password')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-card-header">
                <h1 class="auth-title">🔐 Create New Password</h1>
                <p class="auth-subtitle">
                    Please enter your new password below.
                </p>
            </div>

            <div class="auth-card-body">
                <form method="POST" action="{{ route('password.store') }}">
                    @csrf

                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="form-group">
                        <label class="form-label">Email Address</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $request->email) }}" readonly>
                    </div>

                    <div class="form-group">
                        <label class="form-label">New Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="••••••••" required>

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="form-control" placeholder="••••••••"
                            required>
                    </div>

                    <div style="text-align: right; margin-top: 32px;">
                        <button type="submit" class="btn btn-primary">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
