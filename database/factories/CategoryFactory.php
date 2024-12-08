<?php
namespace Database\Factories;

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CategoryFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}

