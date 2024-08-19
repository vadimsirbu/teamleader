<?php

namespace Tests\Feature;

use App\Discounts\CustomerRevenueDiscount;
use App\Discounts\FreeProductByCategoryDiscount;
use App\Discounts\MoreItemsFromSameCategoryDiscount;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class IsActiveDiscountTest extends TestCase
{
    use RefreshDatabase;

    public function testOnlyActiveDiscountsShowUp(): void
    {
        Discount::factory()->create([
            'entity' => CustomerRevenueDiscount::class,
            'priority' => 1,
            'config' => json_encode([
                'total' => 50,
                'discount' => 0.1,
            ]),
            'is_active' => false,
        ]);

        Discount::factory()->create([
            'entity' => FreeProductByCategoryDiscount::class,
            'priority' => 1,
            'config' => json_encode([
                'category_id' => 2,
                'quantity' => 2,
            ]),
        ]);


        Discount::factory()->create([
            'entity' => MoreItemsFromSameCategoryDiscount::class,
            'priority' => 1,
            'config' => json_encode([
                'category_id' => 2,
                'quantity' => 2,
                'discount' => 0.2,
            ]),
        ]);

        Customer::factory()->create([
            'id' => 1,
            'revenue' => 1000,
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
                    'name' => 'free_product_by_category_discount',
                    'discount_type' => 'free_item',
                    'discount_value' => 2,
                    'discount_item' => 'B102'
                ],
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
}
