<?php

namespace App\Discounts;

use App\DTOs\Discount\FreeProductByCategoryDiscountConfigDto;
use App\DTOs\PipelineDto;
use App\Enums\DiscountTypeEnum;
use App\Repositories\CustomerRepository;
use App\Repositories\ProductRepository;
use Closure;
use Illuminate\Support\Str;

class FreeProductByCategoryDiscount
{
    public function __construct(
        protected CustomerRepository $customerRepository,
        protected ProductRepository $productRepository,
    ) {

    }

    public function handle(PipelineDto $pipelineDto, Closure $next)
    {
        $discount = $pipelineDto->activeDiscounts->get($pipelineDto->currentDiscount);

        $pipelineDto->currentDiscount++;

        if (!$discount->is_stackable && $pipelineDto->result->count() > 0) {
            return $next($pipelineDto);
        }

        $config = FreeProductByCategoryDiscountConfigDto::from($discount->config);

        foreach ($pipelineDto->order->orderItems as $orderItem) {
            $product = $this->productRepository->getProductByIdentifier($orderItem->productIdentifier);

            if ($product?->category_id !== $config->categoryId) {
                continue;
            }

            $freeItems = intval($orderItem->quantity / $config->quantity);

            if ($freeItems > 0) {
                $pipelineDto->result->push([
                    'name' => Str::snake(class_basename(__CLASS__)),
                    'discount_type' => DiscountTypeEnum::FREE_ITEM,
                    'discount_value' => $freeItems,
                    'discount_item' => $orderItem->productIdentifier,
                ]);
            }
        }

        return $next($pipelineDto);
    }
}
