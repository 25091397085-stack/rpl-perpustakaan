<section>
    <!-- Header -->
    <div class="mb-4">
        <h5 class="fw-bold text-danger">Delete Account</h5>
        <p class="text-muted">
            Once your account is deleted, all of its resources and data will be permanently deleted.
        </p>
    </div>

    <!-- Button trigger modal -->
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
        <i class="fas fa-trash-alt me-2"></i> Delete Account
    </button>

    <!-- Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">

                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <!-- Header -->
                    <div class="modal-header">
                        <h5 class="modal-title text-danger">Confirm Delete</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <!-- Body -->
                    <div class="modal-body">
                        <p>
                            Are you sure you want to delete your account? This action cannot be undone.
                        </p>

                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password"
                                class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                placeholder="Enter your password" required>

                            @error('password', 'userDeletion')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i> Cancel
                        </button>

                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-trash-alt me-2"></i> Delete Account
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</section>
