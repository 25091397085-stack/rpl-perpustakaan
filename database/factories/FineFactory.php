<?php

namespace Database\Factories;

use App\Models\Borrowing;
use App\Models\Fine;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fine>
 */
class FineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'borrowing_id' => Borrowing::inRandomOrder()->first()->id,
            'amount' => $this->faker->numberBetween(3000, 50000),
            'payment_status' => $this->faker->randomElement(['belum dibayar', 'sudah dibayar']),
        ];
    }
}
