<?php

namespace Tests\Feature;

use App\Discounts\MoreItemsFromSameCategoryDiscount;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MoreItemsFromSameCategoryDiscountTest extends TestCase
{
    use RefreshDatabase;

    public function testMoreItemsFromSameCategoryDiscountSameProduct(): void
    {
        Discount::factory()->create([
            'entity' => MoreItemsFromSameCategoryDiscount::class,
            'priority' => 1,
            'config' => json_encode([
                'category_id' => 2,
                'quantity' => 2,
                'discount' => 0.2,
            ]),
        ]);

        Product::factory()->create([
            'id' => 1,
            'identifier' => 'B102',
            'category_id' => 2,
        ]);

        $response = $this->post(
            route('discount.calculate'),
            [
                'id' => '1',
                'customer-id' => '1',
                'items' => [
                    [
                        'product-id' => 'B102',
                        'quantity' => 4,
                        'unit-price' => '4.99',
                        'total' => 4 * 4.99,
                    ]
                ],
                'total' => 4 * 4.99,
            ]
        );

        $response->assertStatus(200);

        $this->assertSame(
            [
                [
                    'name' => 'more_items_from_same_category_discount',
                    'discount_type' => 'percentage',
                    'discount_value' => 0.2,
                    'discount_item' => 'B102',
                ],
            ],
            $response->json('data')
        );
    }

    public function testMoreItemsFromSameCategoryDiscountDifferentProducts(): void
    {
        Discount::factory()->create([
            'entity' => MoreItemsFromSameCategoryDiscount::class,
            'priority' => 1,
            'config' => json_encode([
                'category_id' => 2,
                'quantity' => 2,
                'discount' => 0.2,
            ]),
        ]);

        Product::factory()->create([
            'id' => 1,
            'identifier' => 'B102',
            'category_id' => 2,
        ]);

        Product::factory()->create([
            'id' => 2,
            'identifier' => 'B107',
            'category_id' => 2,
        ]);

        $response = $this->post(
            route('discount.calculate'),
            [
                'id' => '1',
                'customer-id' => '1',
                'items' => [
                    [
                        'product-id' => 'B102',
                        'quantity' => 1,
                        'unit-price' => '4.99',
                        'total' => '4.99',
                    ],
                    [
                        'product-id' => 'B107',
                        'quantity' => 1,
                        'unit-price' => '3.99',
                        'total' => '3.99',
                    ]
                ],
                'total' => '8.98',
            ]
        );

        $response->assertStatus(200);

        $this->assertSame(
            [
                [
                    'name' => 'more_items_from_same_category_discount',
                    'discount_type' => 'percentage',
                    'discount_value' => 0.2,
                    'discount_item' => 'B102',
                ],
            ],
            $response->json('data')
        );
    }

    public function testMoreItemsFromSameCategoryDiscountNoDiscount(): void
    {
        Discount::factory()->create([
            'entity' => MoreItemsFromSameCategoryDiscount::class,
            'priority' => 1,
            'config' => json_encode([
                'category_id' => 2,
                'quantity' => 3,
                'discount' => 0.2,
            ]),
        ]);

        Product::factory()->create([
            'id' => 1,
            'identifier' => 'B102',
            'category_id' => 2,
        ]);

        Product::factory()->create([
            'id' => 2,
            'identifier' => 'B107',
            'category_id' => 2,
        ]);

        $response = $this->post(
            route('discount.calculate'),
            [
                'id' => '1',
                'customer-id' => '1',
                'items' => [
                    [
                        'product-id' => 'B102',
                        'quantity' => 1,
                        'unit-price' => '4.99',
                        'total' => '4.99',
                    ],
                    [
                        'product-id' => 'B107',
                        'quantity' => 1,
                        'unit-price' => '3.99',
                        'total' => '3.99',
                    ]
                ],
                'total' => '8.98',
            ]
        );

        $response->assertStatus(200);

        $this->assertSame([], $response->json('data'));
    }

    public function testMoreItemsFromSameCategoryDiscountMissingProductInformation(): void
    {
        Discount::factory()->create([
            'entity' => MoreItemsFromSameCategoryDiscount::class,
            'priority' => 1,
            'config' => json_encode([
                'category_id' => 2,
                'quantity' => 1,
                'discount' => 0.2,
            ]),
        ]);

        $response = $this->post(
            route('discount.calculate'),
            [
                'id' => '1',
                'customer-id' => '1',
                'items' => [
                    [
                        'product-id' => 'B102',
                        'quantity' => 1,
                        'unit-price' => '4.99',
                        'total' => '4.99',
                    ],
                    [
                        'product-id' => 'B107',
                        'quantity' => 1,
                        'unit-price' => '3.99',
                        'total' => '3.99',
                    ]
                ],
                'total' => '8.98',
            ]
        );

        $response->assertStatus(200);

        $this->assertSame([], $response->json('data'));
    }

}
