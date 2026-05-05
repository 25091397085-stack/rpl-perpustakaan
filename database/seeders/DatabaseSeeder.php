<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Models\Category;
use App\Models\Book;
use App\Models\Member;
use App\Models\Borrowing;
use App\Models\Fine;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    // public function run(): void
    // {
    //     // 1. Buat role
    //     $adminRole = Role::firstOrCreate(['name' => 'admin']);
    //     $memberRole = Role::firstOrCreate(['name' => 'member']);

    //     // 2. Buat user admin
    //     $admin = User::create([
    //         'name' => 'Admin',
    //         'email' => 'admin@gmail.com',
    //         'password' => Hash::make('password123'),
    //     ]);

    //     // 3. Assign role admin
    //     $admin->assignRole($adminRole);

    //     // Optional: user biasa
    //     $user = User::create([
    //         'name' => 'User',
    //         'email' => 'user@gmail.com',
    //         'password' => Hash::make('password123'),
    //     ]);

    //     $user->assignRole($memberRole);
    // }
    public function run(): void
    {
        // fake() = Faker::create();

        // // 1. Role
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $memberRole = Role::firstOrCreate(['name' => 'member']);

        // 2. Admin
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password123'),
        ]);
        $admin->assignRole($adminRole);

        // // =========================
        // // 3. KATEGORI (10)
        // // =========================
        // $categories = collect();
        // for ($i = 1; $i <= 10; $i++) {
        //     $categories->push(
        //         Category::create([
        //             'name' => fake()->word
        //         ])
        //     );
        // }

        // // =========================
        // // 4. BUKU (10)
        // // =========================
        // $books = collect();
        // for ($i = 1; $i <= 10; $i++) {
        //     $books->push(
        //         Book::create([
        //             'category_id' => $categories->random()->id,
        //             'cover' => 'default.jpg',
        //             'title' => fake()->sentence(3),
        //             'author' => fake()->name,
        //             'stock' => rand(1, 10),
        //             'sinopsis' => fake()->paragraph
        //         ])
        //     );
        // }

        // // =========================
        // // 5. MEMBER + USER (10)
        // // =========================
        // $members = collect();
        // for ($i = 1; $i <= 10; $i++) {

        //     $user = User::create([
        //         'name' => fake()->name,
        //         'email' => fake()->unique()->safeEmail,
        //         'password' => Hash::make('password123'),
        //     ]);

        //     $user->assignRole($memberRole);

        //     $members->push(
        //         Member::create([
        //             'user_id' => $user->id,
        //             'member_code' => rand(100000, 999999),
        //             'name' => $user->name,
        //             'email' => $user->email,
        //             'address' => fake()->address,
        //             'phone' => fake()->unique()->numerify('08##########'),
        //         ])
        //     );
        // }

        // // =========================
        // // 6. PEMINJAMAN (10)
        // // =========================
        // for ($i = 1; $i <= 10; $i++) {

        //     $borrowDate = now()->subDays(rand(1, 10));
        //     $dueDate = (clone $borrowDate)->addDays(7);

        //     // Random status
        //     $statusList = ['sudah dikembalikan', 'belum dikembalikan', 'terlambat'];
        //     $status = $statusList[array_rand($statusList)];

        //     $returnDate = null;

        //     if ($status != 'belum dikembalikan') {
        //         $returnDate = (clone $borrowDate)->addDays(rand(1, 10));
        //     }

        //     Borrowing::create([
        //         'book_id' => $books->random()->id,
        //         'member_id' => $members->random()->id,
        //         'borrow_date' => $borrowDate,
        //         'due_date' => $dueDate,
        //         'return_date' => $returnDate,
        //         'status' => $status,
        //     ]);
        // }
    }

    // public function run(): void
    // {
    //     Fine::factory(100)->create();
    // }
}
