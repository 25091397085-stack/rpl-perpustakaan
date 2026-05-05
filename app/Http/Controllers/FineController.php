<?php

namespace App\Http\Controllers;

use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FineController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Fine::with('borrowing.member.user', 'borrowing.book');

        // ✅ FILTER STATUS (UNTUK SEMUA ROLE)
        if ($request->filled('status')) {
            $query->where('payment_status', $request->status);
        }

        // ✅ KHUSUS MEMBER → hanya lihat miliknya
        if (!$user->hasRole('admin')) {
            $query->whereHas('borrowing.member', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }

        $fines = $query->latest()->get();

        return view('fines.index', compact('fines'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Fine $fine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Fine $fine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Fine $fine)
    {
        $validated = $request->validate([
            'payment_status' => 'required|in:belum dibayar,sudah dibayar'
        ]);

        $fine->update($validated);

        $message = $validated['payment_status'] === 'sudah dibayar'
            ? 'Denda ditandai sebagai sudah dibayar'
            : 'Denda ditandai sebagai belum dibayar';

        return back()->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Fine $fine)
    {
        //
    }

    public function pay(Fine $fine)
    {
        $fine->update([
            'payment_status' => 'sudah dibayar'
        ]);

        return back()->with('success', 'Denda berhasil dibayar');
    }

    public function late()
    {
        // Ambil semua borrowing yang belum dikembalikan dan due_date sudah lewat
        $overdueBorrowings = \App\Models\Borrowing::where('status', 'belum dikembalikan')
            ->where('due_date', '<', now()->toDateString())
            ->get();

        foreach ($overdueBorrowings as $borrowing) {
            // Cek apakah sudah ada fine untuk borrowing ini
            $existingFine = Fine::where('borrowing_id', $borrowing->id)->first();

            if (!$existingFine) {
                // Hitung hari terlambat
                $lateDays = Carbon::parse($borrowing->due_date)->diffInDays(now());
                $fineAmount = $lateDays * 3000;

                // Buat fine
                Fine::create([
                    'borrowing_id' => $borrowing->id,
                    'amount' => $fineAmount,
                    'payment_status' => 'belum dibayar'
                ]);

                // Update status borrowing ke terlambat
                $borrowing->update(['status' => 'terlambat']);
            }
        }

        return back()->with('success', 'Denda untuk peminjaman terlambat telah dihitung');
    }
}
