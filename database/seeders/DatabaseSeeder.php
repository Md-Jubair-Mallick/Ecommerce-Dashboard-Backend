<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Customer;
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
        Product::factory()->count(50)->create();

        // Create customers
        Customer::factory()->count(20)->create();

        // Create orders and order items
        Order::factory()
            ->count(30)
            ->has(OrderItem::factory()->count(3))
            ->create();
            
        // Create users
        User::factory()
        ->count(5)
        ->create();
    }
}
