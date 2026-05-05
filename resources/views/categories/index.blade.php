@extends('layouts.app')

@section('title', 'Perpustakaan - Kategori')
@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 mb-3">Kategori</h1>

        <div class="card mb-4" style="border: 1px solid #d6caad; border-radius: 10px; overflow: hidden;">

            <div class="card-header d-flex align-items-center justify-content-between"
                style="background: #7a5c38; padding: 11px 18px; border: none;">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-table" style="color: #e8d9c0; font-size: 13px;"></i>
                    <p class="mb-0" style="color: #f5ece0; font-size: 13px; font-weight: 500;">Data Kategori</p>
                </div>

                <a href="{{ route('categories.create') }}"
                    style="display:inline-flex;align-items:center;gap:6px;padding:5px 14px;border-radius:6px;border:1px solid #c9a96e;background:#f0d9b5;color:#4a3018;font-size:12px;font-weight:500;text-decoration:none;"
                    onmouseover="this.style.background='#e8c98a'" onmouseout="this.style.background='#f0d9b5'">
                    <i class="fas fa-plus" style="font-size:11px;"></i>
                    Tambah Kategori
                </a>
            </div>

            <div class="card-body" style="padding: 1.25rem 1rem 1rem;">
                <table id="datatablesSimple">
                    <thead>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>Nama Kategori</th>
                            <th>Aksi</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        @foreach ($categories as $category)
                            <tr>
                                <td style="font-weight: 500; color: #2c2416;">{{ $category->name }}</td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        {{-- Edit --}}
                                        <a href="{{ route('categories.edit', $category->id) }}"
                                            class="d-inline-flex align-items-center justify-content-center"
                                            style="width:30px;height:30px;border-radius:8px;border:1.5px solid #d97706;color:#854f0b;background:transparent;text-decoration:none;"
                                            onmouseover="this.style.background='#fffbeb'"
                                            onmouseout="this.style.background='transparent'" title="Edit">
                                            <i class="fas fa-edit" style="font-size:11px;"></i>
                                        </a>

                                        {{-- Delete --}}
                                        <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
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
