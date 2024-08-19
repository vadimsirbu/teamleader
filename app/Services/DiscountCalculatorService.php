<?php

namespace App\Services;

use App\DTOs\Order\OrderDto;
use App\DTOs\PipelineDto;
use App\Repositories\DiscountRepository;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;

class DiscountCalculatorService
{
    public function __construct(protected DiscountRepository $discountRepository)
    {

    }

    public function calculate(OrderDto $order): Collection
    {
        $activeDiscounts = $this->discountRepository->getActiveDiscounts();

        if ($activeDiscounts->count() === 0) {
            return collect();
        }

        $pipelineDto = new PipelineDto(
            order: $order,
            result: collect(),
            activeDiscounts: $activeDiscounts,
            currentDiscount: 0,
        );

        $pipelineDto = app(Pipeline::class)
            ->send($pipelineDto)
            ->through(
                $activeDiscounts->pluck('entity')->toArray(),
            )
            ->thenReturn();

        return $pipelineDto->result;
    }
}
