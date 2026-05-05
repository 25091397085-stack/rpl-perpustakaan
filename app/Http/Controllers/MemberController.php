<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $members = Member::all();
        return view('members.index', compact('members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('members.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email',
            'address' => 'required|string',
            'phone' => 'required|numeric|unique:members,phone',
        ]);

        DB::beginTransaction();

        try {
            // 1. Buat user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('password123')
            ]);

            // 2. Role (create jika belum ada)
            $role = Role::firstOrCreate(['name' => 'member']);

            // 3. Assign role
            $user->assignRole($role);

            // 4. Generate member_code unik
            do {
                $memberCode = rand(100000, 999999);
            } while (Member::where('member_code', $memberCode)->exists());

            // 5. Simpan member
            Member::create([
                'user_id' => $user->id,
                'member_code' => $memberCode,
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'phone' => $request->phone,
            ]);

            DB::commit();

            return redirect()->route('members.index')
                ->with('success', 'Anggota berhasil ditambahkan');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan anggota: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $request->validate([
            'member_code' => 'required|numeric|unique:members,member_code,' . $member->id,
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:members,email,' . $member->id,
            'address' => 'required|string',
            'phone' => 'required|numeric|unique:members,phone,' . $member->id,
        ]);

        $member->update([
            'member_code' => $request->member_code,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone
        ]);

        // optional: update user juga
        $member->user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        // Hapus data
        $member->delete();

        // Redirect + pesan sukses
        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil dihapus');
    }
}
