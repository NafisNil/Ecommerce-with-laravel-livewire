<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Coupon;
class CouponSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $coupons = [
            ['code' => 'SUMMER10', 'type' => 'percent', 'value' => 10, 'min_order_amount' => 50, 'is_active' => true, 'end_at' => now()->addDays(30)],
            ['code' => 'WINTER15', 'type' => 'percent', 'value' => 15, 'min_order_amount' => 100, 'is_active' => false, 'end_at' => now()->addDays(20)],
            ['code' => 'FALL20', 'type' => 'percent', 'value' => 20, 'min_order_amount' => 150, 'is_active' => true, 'end_at' => now()->addDays(30)],
            ['code' => 'SPRING25', 'type' => 'percent', 'value' => 25, 'min_order_amount' => 200, 'is_active' => false, 'end_at' => now()->addDays(10)],
            ['code' => 'FREESHIP', 'type' => 'fixed', 'value' => 5, 'min_order_amount' => 0, 'is_active' => true, 'end_at' => now()->addDays(30)],
            ['code' => 'WELCOME20', 'type' => 'percent', 'value' => 20, 'min_order_amount' => 0, 'is_active' => true, 'end_at' => now()->addDays(7)],
            ['code' => 'BLACKFRIDAY50', 'type' => 'percent', 'value' => 50, 'min_order_amount' => 0, 'is_active' => true, 'end_at' => now()->addDays(12)],
            ['code' => 'CYBERMONDAY30', 'type' => 'percent', 'value' => 30, 'min_order_amount' => 0, 'is_active' => false, 'end_at' => now()->addDays(13)],
        ];

        foreach ($coupons as $couponData) {
            Coupon::create([
                'code' => $couponData['code'],
                'type' => $couponData['type'],
                'value' => $couponData['value'],
                'min_order_amount' => $couponData['min_order_amount'],
                'max_discount_amount' => $couponData['max_discount_amount'] ?? null,
                'usage_limit' => $couponData['usage_limit'] ?? null,
                'usage_limit_per_user' => $couponData['usage_limit_per_user'] ?? null,
                'is_active' => $couponData['is_active'],
                'start_at' => $couponData['start_at'] ?? null,
                'end_at' => $couponData['end_at'],
                
            ]);
        }
        Coupon::factory()->count(10)->create();
        $this->command->info('Coupons seeded successfully!');
    }
}
