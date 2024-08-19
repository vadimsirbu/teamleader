<?php

namespace App\DTOs;

use App\DTOs\Order\OrderDto;
use Illuminate\Support\Collection;

class PipelineDto
{
    public function __construct(
        public OrderDto   $order,
        public Collection $result,
        public Collection $activeDiscounts,
        public int        $currentDiscount,
    )
    {

    }
}
