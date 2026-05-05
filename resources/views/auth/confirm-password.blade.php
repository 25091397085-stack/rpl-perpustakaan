@extends('layouts.guest')

@section('title', 'Confirm Password')

@section('content')
    <div class="auth-container">
        <div class="auth-card">
            <div class="auth-card-header">
                <h1 class="auth-title">📖 Confirm Password</h1>
                <p class="auth-subtitle">
                    This is a secure area. Please verify your password to continue.
                </p>
            </div>

            <div class="auth-card-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf

                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                            placeholder="Enter your password" required>

                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div style="text-align: right; margin-top: 32px;">
                        <button type="submit" class="btn btn-primary">
                            Confirm Access
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
