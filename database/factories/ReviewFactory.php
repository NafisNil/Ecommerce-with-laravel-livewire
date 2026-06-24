<?php

namespace Database\Factories;

use App\Models\Review;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
use App\Models\Customer;
/**
 * @extends Factory<Review>
 */
class ReviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $rating = $this->faker->numberBetween(1, 5);
        return [
            //
            'product_id' => Product::factory(),
            'customer_id' => Customer::factory(),
            'rating' => $rating,
            'order_id' => null,
            'comment' => $this->faker->paragraph(),
            'title' => $this->faker->sentence(),
            'is_verified' => $this->faker->boolean(),
            'is_approved' => $this->faker->boolean(),
        ];
    }
}
