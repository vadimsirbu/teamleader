<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CustomerFactory extends Factory
{

    public function definition()
    {
        return [
            'name' => fake()->name(),
            'revenue' => fake()->randomFloat(),
            'since' => now()->subDays(fake()->randomNumber(2, false)),
        ];
    }
}
