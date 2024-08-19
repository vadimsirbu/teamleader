<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class DiscountFactory extends Factory
{

    public function definition()
    {
        return [
            'entity' => __CLASS__,
            'priority' => fake()->randomNumber(),
            'config' => '{}',
            'is_stackable' => true,
            'is_active' => true,
        ];
    }
}
