@extends('layouts.app')

@section('title', 'Perpustakaan - Buku')
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 mb-3">Buku</h1>

        <div class="card mb-4" style="border: 1px solid #d6caad; border-radius: 10px; overflow: hidden;">

            <div class="card-header d-flex align-items-center justify-content-between"
                style="background: #7a5c38; padding: 11px 18px; border: none;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-table" style="color: #e8d9c0; font-size: 13px;"></i>
                    <p class="mb-0" style="color: #f5ece0; font-size: 13px; font-weight: 500;">Data Buku</p>
                </div>

                <a href="{{ route('books.create') }}"
                    style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:6px;border:1px solid #c9a96e;background:#f0d9b5;color:#4a3018;font-size:12px;font-weight:500;text-decoration:none;"
                    onmouseover="this.style.background='#e8c98a'" onmouseout="this.style.background='#f0d9b5'">
                    <i class="fas fa-plus" style="font-size:11px;"></i>
                    Tambah Buku
                </a>
            </div>

            <div class="card-body" style="padding:;">
                <table id="datatablesSimple" style="width: 100%;">
                    <thead>
                        <tr>
                            <th style="white-space: nowrap; width: 22%;">Judul Buku</th>
                            <th style="white-space: nowrap; width: 15%;">Kategori</th>
                            <th style="white-space: nowrap; width: 18%;">Penulis</th>
                            <th style="white-space: nowrap; width: 8%;">Stok</th>
                            <th style="white-space: nowrap; width: 25%;">Sinopsis</th>
                            <th style="white-space: nowrap; width: 12%;">Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th style="white-space: nowrap;">Judul Buku</th>
                            <th style="white-space: nowrap;">Kategori</th>
                            <th style="white-space: nowrap;">Penulis</th>
                            <th style="white-space: nowrap;">Stok</th>
                            <th style="white-space: nowrap;">Sinopsis</th>
                            <th style="white-space: nowrap;">Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($books as $book)
                            <tr>
                                <td style="font-weight: 500; color: #2c2416; white-space: nowrap;">{{ $book->title }}</td>
                                <td style="color: #6b5a42; white-space: nowrap;">{{ $book->category->name }}</td>
                                <td style="color: #6b5a42; white-space: nowrap;">{{ $book->author }}</td>
                                <td style="color: #6b5a42; text-align: center;">{{ $book->stock }}</td>
                                <td>
                                    <p style="margin: 0; font-size: 12px; color: #000000; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                        {{ $book->sinopsis }}
                                    </p>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('books.edit', $book->id) }}"
                                            class="d-inline-flex align-items-center justify-content-center"
                                            style="width:30px;height:30px;border-radius:8px;border:1.5px solid #d97706;color:#854f0b;background:transparent;text-decoration:none;"
                                            onmouseover="this.style.background='#fffbeb'"
                                            onmouseout="this.style.background='transparent'" title="Edit">
                                            <i class="fas fa-edit" style="font-size:11px;"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('books.destroy', $book->id) }}" method="POST"
                                            class="d-inline m-0" onsubmit="return confirm('Yakin mau hapus?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="d-inline-flex align-items-center justify-content-center"
                                                style="width:30px;height:30px;border-radius:8px;border:1.5px solid #ef4444;color:#a32d2d;background:transparent;cursor:pointer;"
                                                onmouseover="this.style.background='#fef2f2'"
                                                onmouseout="this.style.background='transparent'" title="Hapus">
                                                <i class="fas fa-trash" style="font-size:11px;"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <style>
        div.dataTables_wrapper div.dataTables_filter {
            margin-bottom: 1rem;
        }

        div.dataTables_wrapper div.dataTables_filter input {
            margin-left: 0.5em;
            margin-top: 0.25rem;
        }
    </style>

@endsection
