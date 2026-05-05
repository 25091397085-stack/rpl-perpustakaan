<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Fine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function home()
    {
        if (Auth::user()->hasRole('admin')) {
            return $this->homeAdmin();
        }

        return $this->homeMember();
    }

    protected function homeAdmin()
    {
        $books = Book::with('category')
            ->latest()
            ->get();

        $bookCount = Book::count();
        $categoryCount = Category::count();
        $activeBorrowings = Borrowing::where('status', 'belum dikembalikan')->count();

        return view('admin.home', compact('books', 'bookCount', 'categoryCount', 'activeBorrowings'));
    }

    protected function homeMember()
    {
        $member = Auth::user()->member;

        if (!$member) {
            return back()->with('error', 'Member tidak ditemukan');
        }

        $borrowCount = Borrowing::where('member_id', $member->id)
            ->where('status', 'belum dikembalikan')
            ->count();

        // Hanya ambil buku yang dipinjam oleh member saat ini
        $userBorrowedBookIds = Borrowing::where('member_id', $member->id)
            ->where('status', 'belum dikembalikan')
            ->pluck('book_id')
            ->toArray();

        $books = Book::with('category')
            ->latest()
            ->get();

        return view('members.home', compact('books', 'borrowCount', 'userBorrowedBookIds'));
    }

    public function borrow(Book $book)
    {
        try {
            DB::beginTransaction();

            // Ambil member dari user login
            $member = Auth::user()->member;

            if (!$member) {
                return back()->with('error', 'Member tidak ditemukan');
            }

            $activeBorrowings = Borrowing::where('member_id', $member->id)
                ->where('status', 'belum dikembalikan')
                ->count();

            if ($activeBorrowings >= 3) {
                return back()->with('error', 'Batas peminjaman maksimal 3 buku telah tercapai');
            }

            if (Borrowing::where('member_id', $member->id)
                ->where('book_id', $book->id)
                ->where('status', 'belum dikembalikan')
                ->exists()
            ) {
                return back()->with('error', 'Anda sudah meminjam buku ini');
            }

            // Cek stok
            if ($book->stock <= 0) {
                return back()->with('error', 'Stok habis');
            }

            // Simpan peminjaman
            Borrowing::create([
                'book_id' => $book->id,
                'member_id' => $member->id,
                'borrow_date' => now(),
                'due_date' => now()->addDays(7),
                'return_date' => null,
                'status' => 'belum dikembalikan'
            ]);

            // Kurangi stok
            $book->decrement('stock');

            DB::commit();

            return back()->with('success', 'Buku berhasil dipinjam');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal meminjam buku');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            DB::beginTransaction();

            $findLate = Borrowing::query()
                ->whereNull('return_date')
                ->where('due_date', '<', now()->toDateString())
                ->get();

            // update status
            // Borrowing::whereIn('id', $findLate->pluck('id'))
            //     ->update([
            //         'status' => 'terlambat'
            //     ]);

            foreach ($findLate as $borrowing) {
                $dueDate = Carbon::parse($borrowing->due_date);
                $todayDate = Carbon::parse(now()->toDateString());
                $lateDays = $dueDate->diffInDays($todayDate);
                $fineAmount = $lateDays * 3000;

                Fine::updateOrCreate(
                    ['borrowing_id' => $borrowing->id], // kunci unik
                    [
                        'amount' => $fineAmount,
                        'payment_status' => 'belum dibayar'
                    ]
                );
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }


        // query tampil data
        $query = Borrowing::with('book', 'member');

        if (Auth::user()->hasRole('member')) {
            $member = Auth::user()->member;
            if ($member) {
                $query->where('member_id', $member->id);
            }
        }

        return view('borrowings.index', [
            'borrowings' => $query->latest()->get()
        ]);
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
    public function show(Borrowing $borrowing)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Borrowing $borrowing)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Borrowing $borrowing)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Borrowing $borrowing)
    {
        //
    }

    public function returnBook(Borrowing $borrowing)
    {
        // Hindari double return
        if ($borrowing->status !== 'belum dikembalikan') {
            return back()->with('error', 'Buku sudah diproses sebelumnya');
        }

        $returnDate = now()->toDateString();
        $dueDate = $borrowing->due_date;
        $isLate = Carbon::parse($returnDate)->gt(Carbon::parse($dueDate));
        $lateDays = 0;
        $fineAmount = 0;

        if ($isLate) {
            $lateDays = Carbon::parse($dueDate)->diffInDays(Carbon::parse($returnDate));
            $fineAmount = $lateDays * 3000;
        }

        $borrowing->update([
            'return_date' => $returnDate,
            'status' => $isLate ? 'terlambat' : 'sudah dikembalikan'
        ]);

        // Update buku
        $borrowing->book->increment('stock'); // +1
        $borrowing->book->update([
            'status' => 'tersedia'
        ]);

        // Jika terlambat, buat denda
        if ($isLate && $fineAmount > 0) {
            Fine::create([
                'borrowing_id' => $borrowing->id,
                'amount' => $fineAmount,
                'payment_status' => 'belum dibayar'
            ]);
        }

        $message = $isLate ? "Buku dikembalikan dengan denda Rp. " . number_format($fineAmount) : 'Buku dikembalikan';
        return back()->with('success', $message);
    }

    public function markLate(Borrowing $borrowing)
    {
        if ($borrowing->status !== 'belum dikembalikan') {
            return back()->with('error', 'Buku sudah diproses sebelumnya');
        }

        // Hitung denda berdasarkan keterlambatan dari due_date hingga hari ini
        $dueDate = $borrowing->due_date;
        $todayDate = now()->toDateString();
        $isLate = Carbon::parse($todayDate)->gt(Carbon::parse($dueDate));
        $lateDays = 0;
        $fineAmount = 0;

        if ($isLate) {
            $lateDays = Carbon::parse($dueDate)->diffInDays(Carbon::parse($todayDate));
            $fineAmount = $lateDays * 3000;
        }

        $borrowing->update([
            'return_date' => $todayDate,
            'status' => 'terlambat'
        ]);

        // Update buku
        $borrowing->book->increment('stock'); // +1
        $borrowing->book->update([
            'status' => 'tersedia'
        ]);

        // Jika terlambat, buat denda
        if ($isLate && $fineAmount > 0) {
            Fine::create([
                'borrowing_id' => $borrowing->id,
                'amount' => $fineAmount,
                'payment_status' => 'belum dibayar'
            ]);
        }

        $message = $isLate ? "Buku ditandai terlambat dengan denda Rp. " . number_format($fineAmount) : 'Buku ditandai terlambat';
        return back()->with('success', $message);
    }
}
