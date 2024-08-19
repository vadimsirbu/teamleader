<?php

namespace Tests\Feature;

use App\Discounts\FreeProductByCategoryDiscount;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FreeProductByCategoryDiscountTest extends TestCase
{
    use RefreshDatabase;

    /** @dataProvider freeProductByCategoryDiscountDataProvider */
    public function testFreeProductByCategoryDiscount(int $quantity, float $discountThreshold, array $expectedDiscounts): void
    {
        Discount::factory()->create([
            'entity' => FreeProductByCategoryDiscount::class,
            'priority' => 1,
            'config' => json_encode([
                'category_id' => 2,
                'quantity' => $discountThreshold,
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
                        'quantity' => $quantity,
                        'unit-price' => '4.99',
                        'total' => $quantity * 4.99,
                    ]
                ],
                'total' => $quantity * 4.99,
            ]
        );

        $response->assertStatus(200);

        $this->assertSame($expectedDiscounts, $response->json('data'));
    }

    public function freeProductByCategoryDiscountDataProvider(): array
    {
        return [
            'has one product discount' => [
                'quantity' => 5,
                'discountThreshold' => 5,
                'expectedDiscounts' => [
                    [
                        'name' => 'free_product_by_category_discount',
                        'discount_type' => 'free_item',
                        'discount_value' => 1,
                        'discount_item' => 'B102'
                    ],
                ],
            ],
            'has multiple products discount' => [
                'quantity' => 14,
                'discountThreshold' => 5,
                'expectedDiscounts' => [
                    [
                        'name' => 'free_product_by_category_discount',
                        'discount_type' => 'free_item',
                        'discount_value' => 2,
                        'discount_item' => 'B102'
                    ],
                ],
            ],
            'no discount' => [
                'quantity' => 4,
                'discountThreshold' => 5,
                'expectedDiscounts' => [],
            ]
        ];
    }


}
