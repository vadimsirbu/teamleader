<?php

namespace App\DTOs\Order;

use Illuminate\Support\Collection;

class OrderDto
{
    public function __construct(
        public int        $id,
        public int        $customerId,
        public Collection $orderItems,
        public float      $total,
    )
    {

    }

    public static function fromRequest(array $data): self
    {
        $orderItems = collect();

        foreach ($data['items'] as $item) {
            $orderItems->push(OrderItemDto::fromRequest($item));
        }

        return new self(
            id: $data['id'],
            customerId: $data['customer-id'],
            orderItems: $orderItems,
            total: $data['total'],
        );
    }
}
