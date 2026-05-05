@extends('layouts.app')

@section('title', 'Perpustakaan - Pinjaman')
@section('content')
    <div class="container-fluid px-4">

        <h1 class="mt-4 mb-3">Peminjaman</h1>

        @if (auth()->user()->hasRole('admin'))

            {{-- ===================== TAMPILAN ADMIN (TIDAK BERUBAH) ===================== --}}
            <div class="card mb-4" style="border: 1px solid #d6caad; border-radius: 10px; overflow: hidden;">
                <div class="card-header d-flex align-items-center justify-content-between"
                    style="background: #7a5c38; padding: 11px 18px; border: none;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-table" style="color: #e8d9c0; font-size: 13px;"></i>
                        <p class="mb-0" style="color: #f5ece0; font-size: 13px; font-weight: 500;">Data Peminjaman</p>
                    </div>
                    <div class="d-flex align-items-center gap-3" style="font-size: 12px; color: #e8d9c0;">
                        <span class="d-flex align-items-center gap-1">
                            <span
                                style="width:7px;height:7px;border-radius:50%;background:#22c55e;display:inline-block;flex-shrink:0;"></span>
                            Sudah Kembali
                        </span>
                        <span class="d-flex align-items-center gap-1">
                            <span
                                style="width:7px;height:7px;border-radius:50%;background:#ef4444;display:inline-block;flex-shrink:0;"></span>
                            Terlambat
                        </span>
                    </div>
                </div>
                <div class="card-body" style="padding: 1.25rem 1rem 1rem;">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Buku</th>
                                <th>Member</th>
                                <th>Tanggal Pinjam</th>
                                <th>Tenggat</th>
                                <th>Tanggal Kembali</th>
                                <th>Status Pengembalian</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($borrowings as $borrowing)
                                <tr>
                                    <td style="font-weight: 500;">{{ $borrowing->book->title }}</td>
                                    <td style="color: #8c7a5e;">{{ $borrowing->member->name }}</td>
                                    <td style="font-variant-numeric: tabular-nums; color: #6b5a42;">
                                        {{ $borrowing->borrow_date }}</td>
                                    <td style="font-variant-numeric: tabular-nums; color: #6b5a42;">
                                        {{ $borrowing->due_date }}</td>
                                    <td style="font-variant-numeric: tabular-nums; color: #6b5a42;">
                                        {{ $borrowing->return_date ?? '—' }}</td>
                                    <td>
                                        @if ($borrowing->status === 'belum dikembalikan')
                                            <div class="d-flex align-items-center gap-2">
                                                <form action="{{ route('borrowings.return', $borrowing->id) }}"
                                                    method="POST" class="m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn-action-custom"
                                                        style="border-color:#22c55e; color:#166534;"
                                                        onclick="return confirm('Kembalikan buku?')">
                                                        <i class="fas fa-check"></i>
                                                    </button>
                                                </form>
                                                <form action="{{ route('borrowings.late', $borrowing->id) }}" method="POST"
                                                    class="m-0">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="btn-action-custom"
                                                        style="border-color:#ef4444; color:#a32d2d;"
                                                        onclick="return confirm('Tandai sebagai terlambat?')">
                                                        <i class="fas fa-exclamation-triangle"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            @php
                                                $statusLabel = match ($borrowing->status) {
                                                    'sudah dikembalikan' => 'Sudah Kembali',
                                                    'terlambat' => 'Terlambat',
                                                    default => ucfirst($borrowing->status),
                                                };
                                                $statusColor =
                                                    $borrowing->status === 'terlambat' ? '#a32d2d' : '#166534';
                                                $statusBorder =
                                                    $borrowing->status === 'terlambat' ? '#ef4444' : '#22c55e';
                                                $statusBg = $borrowing->status === 'terlambat' ? '#fef2f2' : '#f0fdf4';
                                            @endphp
                                            <span
                                                style="display:inline-flex; align-items:center;padding:4px 12px; border-radius:8px; font-size:11.5px; font-weight:500; border:1.5px solid {{ $statusBorder }}; background: {{ $statusBg }}; color: {{ $statusColor }};">
                                                {{ $statusLabel }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @else
            {{-- ===================== TAMPILAN MEMBER (TANPA CARD PEMBUNGKUS) ===================== --}}
            <div class="mb-3">
                <p class="mb-0" style="font-size: 13px; color: #8c7a5e;">{{ $borrowings->count() }} peminjaman tercatat
                </p>
            </div>

            <div class="d-flex flex-column gap-3">
                @foreach ($borrowings as $borrowing)
                    @php
                        [$label, $badgeStyle, $stripColor] = match ($borrowing->status) {
                            'belum dikembalikan' => [
                                'Belum Kembali',
                                'color:#854f0b; border-color:#d97706; background:#fffbeb;',
                                '#d97706',
                            ],
                            'sudah dikembalikan' => [
                                'Sudah Kembali',
                                'color:#166534; border-color:#22c55e; background:#f0fdf4;',
                                '#22c55e',
                            ],
                            'terlambat' => [
                                'Terlambat',
                                'color:#a32d2d; border-color:#fca5a5; background:#fef2f2;',
                                '#ef4444',
                            ],
                            default => [
                                ucfirst($borrowing->status),
                                'color:#374151; border-color:#9ca3af; background:#f9fafb;',
                                '#9ca3af',
                            ],
                        };
                    @endphp

                    {{-- Item List Member dengan Border Radius 8px --}}
                    <div
                        style="background: #faf7f0; border: 1px solid #d6caad; border-radius: 8px; border-left: 4px solid {{ $stripColor }}; overflow: hidden;">
                        <div
                            style="padding: 14px 16px 10px; display: flex; align-items: flex-start; justify-content: space-between; gap: 12px;">
                            <div>
                                <p style="margin: 0; font-weight: 600; font-size: 14px; color: #2c2416; line-height: 1.3;">
                                    {{ $borrowing->book->title }}
                                </p>
                                <p style="margin: 4px 0 0; font-size: 12px; color: #8c7a5e;">
                                    <i class="fas fa-user" style="font-size: 10px; margin-right: 4px;"></i>
                                    {{ $borrowing->member->name }}
                                </p>
                            </div>
                            {{-- Chip Status Member: Tanpa Titik, Radius 8px --}}
                            <span
                                style="display:inline-flex; align-items:center; padding:4px 12px; border-radius:8px; font-size:11px; font-weight:100; border:1.5px solid; flex-shrink:0; {{ $badgeStyle }}">
                                {{ $label }}
                            </span>
                        </div>

                        <div style="height: 1px; background: #e8dfc8; margin: 0 16px;"></div>

                        <div style="padding: 10px 16px 14px; display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px;">
                            <div>
                                <p
                                    style="margin: 0; font-size: 10px; color: #b0a08a; font-weight: 400;">
                                    Pinjam</p>
                                <p style="margin: 3px 0 0; font-size: 12px; color: #4a3b28;">{{ $borrowing->borrow_date }}
                                </p>
                            </div>
                            <div>
                                <p
                                    style="margin: 0; font-size: 10px; color: #b0a08a; font-weight: 400;">
                                    Tenggat</p>
                                <p style="margin: 3px 0 0; font-size: 12px; color: #4a3b28;">{{ $borrowing->due_date }}</p>
                            </div>
                            <div>
                                <p
                                    style="margin: 0; font-size: 10px; color: #b0a08a; font-weight: 400;">
                                    Kembali</p>
                                <p style="margin: 3px 0 0; font-size: 12px; color: #4a3b28;">
                                    {{ $borrowing->return_date ?? '—' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <style>
        .btn-action-custom {
            width: 30px;
            height: 30px;
            border-radius: 8px;
            border: 1.5px solid;
            background: transparent;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .btn-action-custom i {
            font-size: 11px;
        }

        div.dataTables_wrapper div.dataTables_filter {
            margin-bottom: 1rem;
        }

        div.dataTables_wrapper div.dataTables_filter input {
            margin-left: 0.5em;
            margin-top: 0.25rem;
        }
    </style>
@endsection
