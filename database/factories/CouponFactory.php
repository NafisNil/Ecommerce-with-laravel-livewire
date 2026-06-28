<?php

namespace Database\Factories;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Coupon>
 */
class CouponFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $type = $this->faker->randomElement(['percent', 'fixed']);
        $value = $type === 'percent' ? $this->faker->numberBetween(5, 50) : $this->faker->randomFloat(2, 5, 100);
        return [
            'type' => $type,
            'value' => $value,
            'code' => strtoupper($this->faker->unique()->bothify('???###')), // Random code like ABC123
            'min_order_amount' => $this->faker->randomFloat(2, 20, 200), // Random minimum order amount between 20 and 200
            'max_discount_amount' => $this->faker->randomFloat(2, 10, 50), // Random maximum discount amount between 10 and 50
            'usage_limit' => $this->faker->numberBetween(1, 100), // Random usage limit between 1 and 100
            'usage_limit_per_user' => $this->faker->numberBetween(1, 5), // Random usage limit per user between 1 and 5
            'is_active' => $this->faker->boolean(80), // 80% chance of being active
            'start_at' => $this->faker->dateTimeBetween('-1 month', '+1 month'), // Random start date within the last month to next month
            'end_at' => $this->faker->dateTimeBetween('+1 month', '+3 months'), // Random end date within the next 1 to 3 months
        ];
    }
}
