<?php

namespace Database\Factories;

use App\Models\ProductImage;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
/**
 * @extends Factory<ProductImage>
 */
class ProductImageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'variant_id' => null,
            'image_path' => 'products/' . $this->faker->uuid() . '.jpg',
            'alt_text' => $this->faker->words(3, true),
            'sort_order' => $this->faker->numberBetween(0, 10),
            'is_primary' => false,
        ];
    }

    public function primary(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_primary' => true,
                'sort_order' => 1,
            ];
        });
    }
}
