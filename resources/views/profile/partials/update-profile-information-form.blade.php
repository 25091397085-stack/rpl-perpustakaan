<section>
    <!-- Header -->
    <div class="mb-4">
        <h5 class="fw-bold">Profile Information</h5>
        <p class="text-muted">
            Update your account's profile information and email address.
        </p>
    </div>

    @if ($errors->any())
        <div style="background:#fdecea; padding:10px; border-radius:8px; margin-bottom:12px;">
            <ul style="margin:0; font-size:13px; color:#7a2020;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Form kirim verifikasi -->
    <form id="send-verification" method="POST" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <!-- Form utama -->
    <form method="POST" action="{{ route('profile.update') }}">
        @csrf
        @method('PATCH')

        <!-- Name -->
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                value="{{ old('name', $user->name) }}" required>
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Email -->
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                value="{{ old('email', $user->email) }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror

            <!-- Email verification -->
            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div class="mt-2">
                    <small class="text-warning">
                        Your email address is unverified.
                    </small>

                    <br>

                    <button form="send-verification" class="btn btn-link p-0">
                        <i class="fas fa-envelope me-2"></i> Resend verification email
                    </button>

                    @if (session('status') === 'verification-link-sent')
                        <div class="text-success small mt-1">
                            Verification link sent!
                        </div>
                    @endif
                </div>
            @endif
        </div>

        <!-- Button -->
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-primary">
                <i class="fas fa-check-circle me-2"></i> Save
            </button>

            @if (session('status') === 'profile-updated')
                <span class="text-success small">
                    Saved.
                </span>
            @endif
        </div>
    </form>
</section>
