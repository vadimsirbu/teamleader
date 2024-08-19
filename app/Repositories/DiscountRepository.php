<?php

namespace App\Repositories;

use App\Models\Discount;
use Illuminate\Database\Eloquent\Collection;

class DiscountRepository
{
    public static function getActiveDiscounts(): Collection
    {
        return Discount::query()
            ->where('is_active', true)
            ->orderBy('priority')
            ->get();
    }
}
