<?php

namespace App\Discounts;

use App\DTOs\Discount\MoreItemsFromSameCategoryDiscountConfigDto;
use App\DTOs\PipelineDto;
use App\Enums\DiscountTypeEnum;
use App\Repositories\ProductRepository;
use Closure;
use Illuminate\Support\Str;

class MoreItemsFromSameCategoryDiscount
{
    public function __construct(protected ProductRepository $productRepository)
    {

    }

    public function handle(PipelineDto $pipelineDto, Closure $next)
    {
        $discount = $pipelineDto->activeDiscounts->get($pipelineDto->currentDiscount);

        $pipelineDto->currentDiscount++;

        if (!$discount->is_stackable && $pipelineDto->result->count() > 0) {
            return $next($pipelineDto);
        }

        $config = MoreItemsFromSameCategoryDiscountConfigDto::from($discount->config);

        $orderItemsByCategory = [];

        foreach ($pipelineDto->order->orderItems as $orderItem) {
            $product = $this->productRepository->getProductByIdentifier($orderItem->productIdentifier);

            if (!$product) {
                continue;
            }

            $orderItemsByCategory[$product->category_id]['count'] = ($orderItemsByCategory[$product->category_id]['count'] ?? 0) + $orderItem->quantity;
            $orderItemsByCategory[$product->category_id]['items'][] = $orderItem;
        }

        foreach ($orderItemsByCategory as $category) {
            if ($category['count'] < $config->quantity) {
                continue;
            }

            $discountedItem = collect($category['items'])->sortByDesc('price')->first();

            $pipelineDto->result->push([
                'name' => Str::snake(class_basename(__CLASS__)),
                'discount_type' => DiscountTypeEnum::PERCENTAGE,
                'discount_value' => $config->discount,
                'discount_item' => $discountedItem->productIdentifier,
            ]);
        }

        return $next($pipelineDto);
    }
}
