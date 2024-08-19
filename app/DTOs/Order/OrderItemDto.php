<?php

namespace App\DTOs\Order;

class OrderItemDto
{
    public function __construct(
        public string $productIdentifier,
        public int    $quantity,
        public float  $unitPrice,
        public float  $total,
    )
    {

    }

    public static function fromRequest(array $data): self
    {
        return new self(
            productIdentifier: $data['product-id'],
            quantity: intval($data['quantity']),
            unitPrice: floatval($data['unit-price']),
            total: floatval($data['total']),
        );
    }
}
