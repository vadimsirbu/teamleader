<?php

namespace App\DTOs\Discount;

class FreeProductByCategoryDiscountConfigDto
{
    public function __construct(
        public int $categoryId,
        public int $quantity,
    )
    {

    }

    public static function from(string $config): self
    {
        $decodedConfig = json_decode($config, true);

        return new self(
            categoryId: intval($decodedConfig['category_id']),
            quantity: intval($decodedConfig['quantity']),
        );
    }
}
