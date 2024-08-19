<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{

    public function definition()
    {
        return [
            'identifier' => fake()->word(),
            'description' => fake()->sentence(),
            'category_id' => fake()->randomNumber(),
            'price' => fake()->randomFloat(1, 0, 1000),
        ];
    }
}
