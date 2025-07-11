<?php

namespace Database\Seeders;

use App\Models\Discount;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $discounts = [
            [
                'code' => 'SALE10',
                'type' => 'percentage',
                'value' => 10,
                'max_discount' => 50000,
                'usage_limit' => 100,
                'used' => 0,
                'start_date' => now()->subDays(1),
                'end_date' => now()->addDays(30),
                'is_active' => true,
            ],
            [
                'code' => 'FREESHIP',
                'type' => 'free_shipping',
                'value' => 0,
                'usage_limit' => 50,
                'used' => 0,
                'start_date' => now(),
                'end_date' => now()->addDays(15),
                'is_active' => true,
            ],
        ];

        foreach ($discounts as $data) {
            Discount::create($data);
        }
    }
}
