<section>
    <!-- Header -->
    <div class="mb-4">
        <h5 class="fw-bold">Update Password</h5>
        <p class="text-muted">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </div>

    <!-- Form -->
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        @method('PUT')

        <!-- Current Password -->
        <div class="mb-3">
            <label class="form-label">Current Password</label>
            <input type="password" name="current_password"
                class="form-control @error('current_password', 'updatePassword') is-invalid @enderror"
                autocomplete="current-password">
            @error('current_password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- New Password -->
        <div class="mb-3">
            <label class="form-label">New Password</label>
            <input type="password" name="password"
                class="form-control @error('password', 'updatePassword') is-invalid @enderror"
                autocomplete="new-password">
            @error('password', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div class="mb-3">
            <label class="form-label">Confirm Password</label>
            <input type="password" name="password_confirmation"
                class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror"
                autocomplete="new-password">
            @error('password_confirmation', 'updatePassword')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Button -->
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-primary">
                <i class="fas fa-key me-2"></i> Perbarui Password
            </button>

            @if (session('status') === 'password-updated')
                <span class="text-success small">
                    Password updated!
                </span>
            @endif
        </div>
    </form>
</section>
