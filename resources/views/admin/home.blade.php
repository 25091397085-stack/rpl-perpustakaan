@extends('layouts.app')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 mb-3">Daftar Buku</h1>

        @if (session('success'))
            <div class="alert-success-minimal alert-auto-hide" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div
                style="background:#fdecea; border:1px solid #f5b8b4; color:#7a2020; border-radius:8px; padding:10px 14px; font-size:13px; margin:12px 0;">
                {{ session('error') }}
            </div>
        @endif

        <style>
            .search-wrap {
                position: relative;
                width: 100%;
                margin-top: 18px;
            }

            .search-wrap i {
                position: absolute;
                left: 12px;
                top: 50%;
                transform: translateY(-50%);
                color: #a0693a;
                font-size: 13px;
                pointer-events: none;
            }

            #searchBooks {
                width: 100%;
                padding-left: 34px;
                background: #fff8e7;
                border: 1px solid #d2b48c;
                border-radius: 8px;
                font-size: 13px;
                color: #2e1c0e;
                height: 38px;
                text-align: center;
            }

            #searchBooks:focus {
                outline: none;
                border-color: #8b6b4a;
                box-shadow: 0 0 0 3px rgba(107, 66, 38, 0.12);
            }

            #searchBooks::placeholder {
                color: #c4a07a;
            }

            .book-grid {
                display: grid;
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: 16px;
                margin-top: 20px;
            }

            @media (min-width: 576px) {
                .book-grid {
                    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
                }
            }

            @media (min-width: 992px) {
                .book-grid {
                    grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
                }
            }

            .book-item {
                cursor: pointer;
            }

            .book-cover-wrap {
                position: relative;
                border-radius: 8px;
                overflow: hidden;
                aspect-ratio: 3/4;
                background: #e8d9c4;
            }

            .book-cover-wrap img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                transition: transform 0.3s, filter 0.3s;
                display: block;
            }

            .alert-success-minimal {
                background: rgba(154, 196, 121, 0.16);
                border: 1px solid rgba(154, 196, 121, 0.3);
                color: #2e5c2e;
                border-radius: 12px;
                padding: 10px 14px;
                font-size: 13px;
                margin: 12px 0;
            }

            .admin-info-row {
                display: flex;
                flex-wrap: wrap;
                gap: 12px;
                margin-top: 18px;
            }

            .admin-info-card {
                flex: 1 1 180px;
                min-width: 180px;
                background: #fff8e7;
                border: 1px solid #d2b48c;
                border-radius: 12px;
                padding: 12px 14px;
                color: #2e1c0e;
                display: flex;
                align-items: center;
                gap: 12px;
            }

            .admin-info-card i {
                font-size: 18px;
                color: #8b6b4a;
            }

            .admin-info-card strong {
                display: block;
                font-size: 18px;
                line-height: 1;
                color: #2e1c0e;
            }

            .admin-info-card span {
                display: block;
                font-size: 12px;
                color: #6b4226;
            }

            .book-item:hover .book-cover-wrap img {
                transform: scale(1.04);
                filter: brightness(0.88);
            }

            .book-overlay {
                position: absolute;
                inset: 0;
                background: linear-gradient(to top, rgba(28, 16, 8, 0.55) 0%, transparent 55%);
                opacity: 0;
                transition: opacity 0.3s;
                display: flex;
                align-items: flex-end;
                padding: 10px;
            }

            .book-item:hover .book-overlay {
                opacity: 1;
            }

            .book-overlay-text {
                font-size: 12px;
                color: #f5e6ca;
                font-weight: 500;
                line-height: 1.3;
            }

            .stock-badge {
                position: absolute;
                top: 8px;
                right: 8px;
                font-size: 10px;
                padding: 3px 7px;
                border-radius: 20px;
                font-weight: 500;
            }

            .stock-empty {
                background: rgba(160, 40, 30, 0.85);
                color: #ffe8e6;
            }

            .book-meta {
                margin-top: 8px;
                padding: 0 2px;
            }

            .book-meta-title {
                font-size: 13px;
                font-weight: 500;
                color: #2e1c0e;
                line-height: 1.3;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            .book-meta-author {
                font-size: 11px;
                color: #a0693a;
                margin-top: 2px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            /* Modal */
            .modal-content {
                border-radius: 12px;
                border: 1px solid #d2b48c;
                background: #fff8e7;
                overflow: hidden;
            }

            .modal-header {
                background: #4a2e18;
                border-bottom: none;
                padding: 14px 20px;
            }

            .modal-title {
                font-family: 'Playfair Display', serif;
                font-size: 17px;
                color: #f5e6ca;
                font-weight: 600;
            }

            .btn-close {
                filter: invert(0.8) sepia(0.3) hue-rotate(10deg);
                opacity: 0.8;
            }

            .btn-close:hover {
                opacity: 1;
            }

            .modal-body {
                padding: 20px;
            }

            .modal-cover {
                width: 100%;
                aspect-ratio: 3/4;
                object-fit: cover;
                border-radius: 8px;
                border: 1px solid #d2b48c;
            }

            .detail-row {
                display: flex;
                align-items: flex-start;
                gap: 8px;
                padding: 8px 0;
                border-bottom: 1px solid rgba(210, 180, 140, 0.3);
                font-size: 13px;
            }

            .detail-row:last-of-type {
                border-bottom: none;
            }

            .detail-label {
                min-width: 72px;
                color: #a0693a;
                font-size: 12px;
                padding-top: 1px;
                flex-shrink: 0;
            }

            .detail-value {
                color: #2e1c0e;
                line-height: 1.4;
            }

            .badge-available {
                background: #d4edda;
                color: #1e5c2e;
                padding: 2px 10px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 500;
            }

            .badge-empty {
                background: #fde8e8;
                color: #8b2020;
                padding: 2px 10px;
                border-radius: 20px;
                font-size: 12px;
                font-weight: 500;
            }

            .sinopsis-box {
                background: #f4ecd8;
                border-radius: 8px;
                padding: 12px 14px;
                font-size: 13px;
                color: #4a2e18;
                line-height: 1.7;
                margin-top: 10px;
                border: 1px solid rgba(210, 180, 140, 0.4);
            }

            @media (max-width: 575px) {
                .modal-cover-col {
                    margin-bottom: 16px;
                }

                .modal-body {
                    padding: 16px;
                }
            }
        </style>

        <div class="admin-info-row">
            <div class="admin-info-card">
                <i class="fa-solid fa-book"></i>
                <div>
                    <strong>{{ $bookCount ?? $books->count() }}</strong>
                    <span>Buku tersedia</span>
                </div>
            </div>
            <div class="admin-info-card">
                <i class="fa-brands fa-stack-overflow"></i>
                <div>
                    <strong>{{ $activeBorrowings }}</strong>
                    <span>Pinjaman aktif</span>
                </div>
            </div>
        </div>

        <div class="search-wrap">
            <input type="text" id="searchBooks" class="form-control" placeholder="Cari judul atau kategori...">
        </div>

        <!-- Book Grid -->
        <div class="book-grid" id="bookGrid">
            @foreach ($books as $book)
                <div class="book-item" data-bs-toggle="modal" data-bs-target="#bookModal{{ $book->id }}"
                    data-title="{{ strtolower($book->title) }}" data-category="{{ strtolower($book->category->name) }}">
                    <div class="book-cover-wrap">
                        <img src="{{ asset('storage/' . $book->cover) }}" alt="{{ $book->title }}">
                        @if ($book->stock == 0)
                            <span class="stock-badge stock-empty">Habis</span>
                        @endif
                        {{-- <div class="book-overlay">
                                <div class="book-overlay-text">{{ $book->title }}</div>
                            </div> --}}
                    </div>
                    <div class="book-meta">
                        <div class="book-meta-title">{{ $book->title }}</div>
                        <div class="book-meta-author">{{ $book->author }}</div>
                    </div>
                </div>

                <!-- Modal -->
                <div class="modal fade" id="bookModal{{ $book->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">{{ $book->title }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row g-3">
                                    <div class="col-5 col-sm-4 modal-cover-col">
                                        <img src="{{ asset('storage/' . $book->cover) }}" class="modal-cover"
                                            alt="{{ $book->title }}">
                                    </div>
                                    <div class="col-7 col-sm-8">
                                        <div class="detail-row">
                                            <span class="detail-label">Kategori</span>
                                            <span class="detail-value">{{ $book->category->name }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Penulis</span>
                                            <span class="detail-value">{{ $book->author }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Stok</span>
                                            <span class="detail-value">{{ $book->stock }}</span>
                                        </div>
                                        <div class="detail-row">
                                            <span class="detail-label">Status</span>
                                            <span class="detail-value">
                                                @if ($book->stock > 0)
                                                    <span class="badge-available">Tersedia</span>
                                                @else
                                                    <span class="badge-empty">Kosong</span>
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                @if ($book->sinopsis)
                                    <div class="sinopsis-box">
                                        <div
                                            style="font-size:11px; font-weight:600; letter-spacing:0.08em; text-transform:uppercase; color:#a0693a; margin-bottom:6px;">
                                            Sinopsis</div>
                                        {{ $book->sinopsis }}
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Empty state -->
        <div id="emptyState" style="display:none; text-align:center; padding:60px 20px; color:#a0693a;">
            <i class="fa-solid fa-book-open" style="font-size:32px; opacity:0.4; margin-bottom:12px; display:center;"></i>
            <p style="font-size:14px;">Tidak ada buku yang ditemukan.</p>
        </div>
    </div>

    <script>
        const successAlert = document.querySelector('.alert-auto-hide');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.4s';
                successAlert.style.opacity = '0';
                setTimeout(() => successAlert.style.display = 'none', 400);
            }, 3000);
        }

        const searchInput = document.getElementById('searchBooks');
        const bookItems = document.querySelectorAll('.book-item');
        const emptyState = document.getElementById('emptyState');

        searchInput.addEventListener('input', function() {
            const term = this.value.toLowerCase().trim();
            let visible = 0;

            bookItems.forEach(item => {
                const title = item.dataset.title || '';
                const category = item.dataset.category || '';
                const match = !term || title.includes(term) || category.includes(term);
                item.style.display = match ? '' : 'none';
                if (match) visible++;
            });

            emptyState.style.display = visible === 0 ? 'block' : 'none';
        });
    </script>
@endsection
