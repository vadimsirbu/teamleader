<?php

namespace Tests\Feature;

use App\Discounts\CustomerRevenueDiscount;
use App\Models\Discount;
use App\Models\Customer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CustomerRevenueDiscountTest extends TestCase
{
    use RefreshDatabase;

    /** @dataProvider customerRevenueDiscountDataProvider */
    public function testCustomerRevenueDiscount(float $revenue, float $discountThreshold, array $expectedDiscounts): void
    {
        Discount::factory()->create([
            'entity' => CustomerRevenueDiscount::class,
            'priority' => 1,
            'config' => json_encode([
                'total' => $discountThreshold,
                'discount' => 0.1,
            ]),
        ]);

        Customer::factory()->create([
            'id' => 1,
            'revenue' => $revenue,
        ]);

        $response = $this->post(
            route('discount.calculate'),
            [
                'id' => '1',
                'customer-id' => '1',
                'items' => [
                    [
                        'product-id' => 'B102',
                        'quantity' => '10',
                        'unit-price' => '4.99',
                        'total' => '49.90',
                    ]
                ],
                'total' => '49.90'
            ]
        );

        $response->assertStatus(200);

        $this->assertSame($expectedDiscounts, $response->json('data'));
    }

    public function customerRevenueDiscountDataProvider(): array
    {
        return [
            'has discount' => [
                'revenue' => 500,
                'discountThreshold' => 500,
                'expectedDiscounts' => [
                    [
                        'name' => 'customer_revenue_discount',
                        'discount_type' => 'percentage',
                        'discount_value' => 0.1,
                        'discount_item' => null
                    ],
                ],
            ],
            'no discount' => [
                'revenue' => 499,
                'discountThreshold' => 500,
                'expectedDiscounts' => [],
            ]
        ];
    }


}
