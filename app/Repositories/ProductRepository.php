<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository
{
    public static function getProductByIdentifier(string $identifier): ?Product
    {
        return Product::where('identifier', $identifier)->first();
    }
}
