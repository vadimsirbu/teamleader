<?php

namespace Database\Seeders;

use App\Discounts\CustomerRevenueDiscount;
use App\Discounts\FreeProductByCategoryDiscount;
use App\Discounts\MoreItemsFromSameCategoryDiscount;
use App\Models\Customer;
use App\Models\Discount;
use App\Models\Product;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        Customer::create([
            'id' => 1,
            'name' => 'Coca Cola',
            'since' => '2014-06-28',
            'revenue' => '492.12',
        ]);

        Customer::create([
            'id' => 2,
            'name' => 'Teamleader',
            'since' => '2015-01-15',
            'revenue' => '1505.95',
        ]);

        Customer::create([
            'id' => 3,
            'name' => 'Jeroen De Wit',
            'since' => '2016-02-11',
            'revenue' => '0.00',
        ]);

        Product::create([
            'id' => 1,
            'identifier' => 'A101',
            'description' => 'Screwdriver',
            'category_id' => '1',
            'price' => '9.75',
        ]);

        Product::create([
            'id' => 2,
            'identifier' => 'A102',
            'description' => 'Electric screwdriver',
            'category_id' => '1',
            'price' => '49.50',
        ]);

        Product::create([
            'id' => 3,
            'identifier' => 'B101',
            'description' => 'Basic on-off switch',
            'category_id' => '2',
            'price' => '4.99',
        ]);

        Product::create([
            'id' => 4,
            'identifier' => 'B102',
            'description' => 'Press button',
            'category_id' => '2',
            'price' => '4.99',
        ]);

        Product::create([
            'id' => 5,
            'identifier' => 'B103',
            'description' => 'Switch with motion detector',
            'category_id' => '2',
            'price' => '12.95',
        ]);

        Discount::create([
            'entity' => CustomerRevenueDiscount::class,
            'priority' => 1,
            'config' => json_encode([
                'total' => 1000,
                'discount' => 0.1,
            ]),
        ]);

        Discount::create([
            'entity' => FreeProductByCategoryDiscount::class,
            'priority' => 2,
            'config' => json_encode([
                'category_id' => 2,
                'quantity' => 5,
            ]),
        ]);

        Discount::create([
            'entity' => MoreItemsFromSameCategoryDiscount::class,
            'priority' => 3,
            'config' => json_encode([
                'category_id' => 2,
                'quantity' => 2,
                'discount' => 0.2,
            ]),
        ]);
    }
}
