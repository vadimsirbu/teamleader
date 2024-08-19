<?php

namespace App\DTOs\Discount;

class CustomerRevenueDiscountConfigDto
{
    public function __construct(
        public float $discount,
        public float $total,
    )
    {

    }

    public static function from(string $config): self
    {
        $decodedConfig = json_decode($config, true);

        return new self(
            discount: floatval($decodedConfig['discount']),
            total: floatval($decodedConfig['total']),
        );
    }
}
