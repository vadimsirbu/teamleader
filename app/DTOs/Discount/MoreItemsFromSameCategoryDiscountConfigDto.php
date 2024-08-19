<?php

namespace App\DTOs\Discount;

class MoreItemsFromSameCategoryDiscountConfigDto
{
    public function __construct(
        public int   $categoryId,
        public float $discount,
        public int   $quantity,
    )
    {

    }

    public static function from(string $config): self
    {
        $decodedConfig = json_decode($config, true);

        return new self(
            categoryId: intval($decodedConfig['category_id']),
            discount: floatval($decodedConfig['discount']),
            quantity: intval($decodedConfig['quantity']),
        );
    }
}
