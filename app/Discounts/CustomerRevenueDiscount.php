<?php

namespace App\Discounts;

use App\DTOs\Discount\CustomerRevenueDiscountConfigDto;
use App\DTOs\PipelineDto;
use App\Enums\DiscountTypeEnum;
use App\Repositories\CustomerRepository;
use Closure;
use Illuminate\Support\Str;

class CustomerRevenueDiscount
{
    public function __construct(protected CustomerRepository $customerRepository)
    {

    }

    public function handle(PipelineDto $pipelineDto, Closure $next)
    {
        $discount = $pipelineDto->activeDiscounts->get($pipelineDto->currentDiscount);

        $pipelineDto->currentDiscount++;

        if (!$discount->is_stackable && $pipelineDto->result->count() > 0) {
            return $next($pipelineDto);
        }

        $customer = $this->customerRepository->findById($pipelineDto->order->customerId);

        if (!$customer) {
            return $next($pipelineDto);
        }

        $config = CustomerRevenueDiscountConfigDto::from($discount->config);

        if ($customer->revenue >= $config->total) {
            $pipelineDto->result->push([
                'name' => Str::snake(class_basename(__CLASS__)),
                'discount_type' => DiscountTypeEnum::PERCENTAGE,
                'discount_value' => $config->discount,
                'discount_item' => null,
            ]);
        }

        return $next($pipelineDto);
    }
}
