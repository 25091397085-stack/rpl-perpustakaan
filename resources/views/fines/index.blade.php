@extends('layouts.app')

@section('title', 'Perpustakaan - Denda')

@section('content')
    <div class="container-fluid px-4">
        <h1 class="mt-4 mb-3">Denda</h1>

        @role('admin')
            {{-- ===================== TAMPILAN ADMIN ===================== --}}
            <div class="card mb-4" style="border: 1px solid #d6caad; border-radius: 10px; overflow: hidden;">

                <div class="card-header d-flex align-items-center justify-content-between"
                    style="background: #7a5c38; padding: 11px 18px; border: none;">
                    <div class="d-flex align-items-center gap-2">
                        <i class="fas fa-table" style="color: #e8d9c0; font-size: 13px;"></i>
                        <p class="mb-0" style="color: #f5ece0; font-size: 13px; font-weight: 500;">Data Denda</p>
                    </div>

                    <div class="d-flex align-items-center gap-3">
                        <div class="d-flex align-items-center gap-3" style="font-size: 12px; color: #e8d9c0;">
                            <span class="d-flex align-items-center gap-1">
                                <span
                                    style="width:7px;height:7px;border-radius:50%;background:#22c55e;display:inline-block;flex-shrink:0;"></span>
                                Sudah Bayar
                            </span>
                            <span class="d-flex align-items-center gap-1">
                                <span
                                    style="width:7px;height:7px;border-radius:50%;background:#ef4444;display:inline-block;flex-shrink:0;"></span>
                                Belum Bayar
                            </span>
                        </div>

                        <form method="GET" action="{{ route('fines.index') }}" class="d-flex gap-2 align-items-center">
                            <select name="status"
                                style="font-size: 12px; border: 1px solid #c9a96e; background: #f5ece0; color: #4a3018; border-radius: 6px; padding: 4px 10px; cursor: pointer; outline: none;">
                                <option value="">Semua Status</option>
                                <option value="sudah dibayar" {{ request('status') == 'sudah dibayar' ? 'selected' : '' }}>Sudah
                                    Bayar</option>
                                <option value="belum dibayar" {{ request('status') == 'belum dibayar' ? 'selected' : '' }}>Belum
                                    Bayar</option>
                            </select>
                            <button type="submit"
                                style="font-size: 12px; padding: 4px 14px; border-radius: 6px; border: 1px solid #c9a96e; background: #f0d9b5; color: #4a3018; cursor: pointer; font-weight: 500;"
                                onmouseover="this.style.background='#e8c98a'" onmouseout="this.style.background='#f0d9b5'">
                                Filter
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card-body" style="padding: 1.25rem 1rem 1rem;">
                    <table id="datatablesSimple">
                        <thead>
                            <tr>
                                <th>Member</th>
                                <th>Judul Buku</th>
                                <th>Denda</th>
                                <th>Status Pembayaran</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr>
                                <th>Member</th>
                                <th>Judul Buku</th>
                                <th>Denda</th>
                                <th>Status Pembayaran</th>
                            </tr>
                        </tfoot>
                        <tbody>
                            @if ($fines->count() > 0)
                                @foreach ($fines as $fine)
                                    <tr>
                                        <td style="color: #8c7a5e;">{{ $fine->borrowing->member->user->name ?? '-' }}</td>
                                        <td style="font-weight: 500; color: #2c2416;">{{ $fine->borrowing->book->title ?? '-' }}
                                        </td>
                                        <td style="font-weight: 600; color: #2c2416;">Rp
                                            {{ number_format($fine->amount, 0, ',', '.') }}</td>
                                        <td>
                                            @if ($fine->payment_status == 'sudah dibayar')
                                                <form action="{{ route('fines.update', $fine->id) }}" method="POST"
                                                    class="d-inline m-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="payment_status" value="belum dibayar">
                                                    <button type="submit" class="d-inline-flex align-items-center gap-1"
                                                        style="padding:4px 12px;border-radius:8px;font-size:11.5px;font-weight:500;border:1.5px solid #22c55e;color:#166534;background:#f0fdf4;cursor:pointer;"
                                                        onmouseover="this.style.background='#dcfce7'"
                                                        onmouseout="this.style.background='#f0fdf4'"
                                                        onclick="return confirm('Ubah status menjadi belum dibayar?')">
                                                        <span style="justify-content:center"></span>
                                                        Sudah Bayar
                                                    </button>
                                                </form>
                                            @else
                                                <form action="{{ route('fines.update', $fine->id) }}" method="POST"
                                                    class="d-inline m-0">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="payment_status" value="sudah dibayar">
                                                    <button type="submit" class="d-inline-flex align-items-center gap-1"
                                                        style="padding:4px 12px;border-radius:8px;font-size:11.5px;font-weight:500;border:1.5px solid #ef4444;color:#a32d2d;background:#fef2f2;cursor:pointer;"
                                                        onmouseover="this.style.background='#fee2e2'"
                                                        onmouseout="this.style.background='#fef2f2'"
                                                        onclick="return confirm('Tandai sebagai sudah dibayar?')">
                                                        <span style="justify-content:center"></span>
                                                        Belum Bayar
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4" class="text-center py-5" style="color: #b0a08a;">
                                        <i class="fas fa-inbox me-2"></i>Tidak ada data denda
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        @endrole

        @role('member')
            {{-- ===================== TAMPILAN MEMBER ===================== --}}
            <div class="mb-3 d-flex align-items-center justify-content-between">
                <p class="mb-0" style="font-size: 13px; color: #8c7a5e;">{{ $fines->count() }} denda tercatat</p>
                <div class="d-flex align-items-center gap-2" style="font-size: 11.5px; color: #8c7a5e;">
                    <span style="width:7px;height:7px;border-radius:50%;background:#22c55e;display:inline-block;"></span> Sudah
                    Bayar
                    <span
                        style="width:7px;height:7px;border-radius:50%;background:#ef4444;display:inline-block;margin-left:6px;"></span>
                    Belum Bayar
                </div>
            </div>

            @if ($fines->count() > 0)
                <div class="d-flex flex-column gap-3">
                    @foreach ($fines as $fine)
                        @php
                            $paid = $fine->payment_status == 'sudah dibayar';
                            $stripColor = $paid ? '#22c55e' : '#ef4444';
                            $dotColor = $paid ? '#22c55e' : '#ef4444';
                            $badgeStyle = $paid
                                ? 'color:#166534;border-color:#22c55e;background:#f0fdf4;'
                                : 'color:#a32d2d;border-color:#ef4444;background:#fef2f2;';
                            $chipStyle = $paid
                                ? 'color:#166534;background:#f0fdf4;border:1px solid #86efac;'
                                : 'color:#a32d2d;background:#fef2f2;border:1px solid #fca5a5;';
                            $chipIcon = $paid ? 'fa-check-circle' : 'fa-clock';
                            $chipLabel = $paid ? 'Sudah Bayar' : 'Belum Bayar';
                            $label = $paid ? 'sudah dibayar' : 'belum dibayar';
                        @endphp

                        <div
                            style="background:#faf7f0;border:1px solid #d6caad;border-radius:12px;border-left:4px solid {{ $stripColor }};overflow:hidden;">

                            {{-- Chip + Judul + Member (tengah, tanpa divider) --}}
                            <div style="padding:16px;display:flex;align-items:center;gap:14px;">

                                {{-- Chip Info kiri --}}
                                <div
                                    style="font-size:11px;border-radius:8px;padding:8px 12px;text-align:center;line-height:1.5;flex-shrink:0;{{ $chipStyle }}">
                                    {{-- <i class="fas {{ $chipIcon }}"
                                        style="display:block;font-size:12px;margin-bottom:3px;"></i> --}}
                                    {{ $chipLabel }}
                                </div>

                                {{-- Divider vertikal --}}
                                <div style="width:1px;height:40px;background:#e8dfc8;flex-shrink:0;"></div>

                                {{-- Judul & Member (tengah) --}}
                                <div style="flex:1;min-width:0;">
                                    <p
                                        style="margin:0;font-weight:600;font-size:14px;color:#2c2416;line-height:1.3;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                                        {{ $fine->borrowing->book->title ?? '-' }}
                                    </p>
                                    <p
                                        style="margin:4px 0 0;font-size:12px;color:#8c7a5e;display:flex;align-items:center;gap:4px;">
                                        <i class="fas fa-user" style="font-size:10px;"></i>
                                        {{ $fine->borrowing->member->user->name ?? '-' }}
                                    </p>
                                </div>

                                {{-- Total Denda kanan --}}
                                <div style="text-align:right;flex-shrink:0;">
                                    <p
                                        style="margin:0;font-size:10px;color:#b0a08a;text-transform:uppercase;letter-spacing:0.05em;font-weight:500;">
                                        Total Denda</p>
                                    <p style="margin:3px 0 0;font-size:15px;font-weight:700;color:#7a5c38;">
                                        Rp {{ number_format($fine->amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div style="text-align:center;padding:3rem 1rem;color:#b0a08a;">
                    <i class="fas fa-inbox" style="font-size:32px;margin-bottom:12px;display:block;"></i>
                    <p style="margin:0;font-size:14px;">Tidak ada data denda</p>
                </div>
            @endif
        @endrole

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
