<?php

namespace Database\Factories;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Product;
/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $color = $this->faker->randomElement(['Red', 'Blue', 'Green', 'Black', 'White']);
        $size = $this->faker->randomElement(['S', 'M', 'L', 'XL', 'XXL']);
        $name = $color . ' - ' . $size;
        $price = $this->faker->randomFloat(2, 10, 100); // Random price between 10 and 100
        return [
            'product_id' => Product::factory(),
            'name' => $name,
            'sku' => 'VAR-' . Str::upper(Str::random(8)),
            'options' => ['color' => $color, 'size' => $size],
            'price' => $price,
            'compare_price' => $this->faker->randomFloat(2, $price, $price + 50),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'stock_status' => $this->faker->randomElement(['in_stock', 'out_of_stock']),
            'is_active' => $this->faker->boolean(80),
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
