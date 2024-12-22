<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
use App\Models\Review;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create categories
        Category::factory()->count(10)->create();

        // Create products
        Product::factory()->count(100)->create();

        // Create customers
        Customer::factory()->count(20)->create();

        // Create orders and their items
        Order::factory()
            ->count(100)
            ->has(OrderItem::factory()->count(5), 'orderItems') // Ensure 'orderItems' relationship exists
            ->create();

        // Create reviews
        Review::factory()->count(100)->create();

        // Create users (optional)
        // User::factory()->count(5)->create();
    }
}
