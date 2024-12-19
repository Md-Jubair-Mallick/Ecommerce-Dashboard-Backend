<?php

namespace Database\Factories;

use App\Models\Customer;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'comment' => $this->faker->sentence(rand(10, 20)), // Generate more realistic sentences
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'rating' => $this->faker->numberBetween(1, 5),
            'customer_id' => Customer::inRandomOrder()->value('id') ?? Customer::factory(), // Use existing customer or create one
            'product_id' => Product::inRandomOrder()->value('id') ?? Product::factory(),   // Use existing product or create one
            'created_at' => $this->faker->dateTimeBetween('-6 months', 'now'), // Random date in the last 6 months
            'updated_at' => now(),
        ];
    }
}
