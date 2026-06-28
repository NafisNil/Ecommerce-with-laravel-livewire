<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Brand;
/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        $name = $this->faker->words(3, true);
        $price = $this->faker->randomFloat(2, 10, 1000);
        $comparePrice = $this->faker->randomFloat(2, $price, 1500);
        return [
            //
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'name' => $name,
            'slug' => Str::slug($name),
            'sku' => $this->faker->unique()->bothify('???-#####'),
            'short_description' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'price' => $price,
            'compare_price' => $comparePrice,
            'cost_price' => $this->faker->randomFloat(2, 5, $price),
            'stock_quantity' => $this->faker->numberBetween(0, 100),
            'stock_threshold' => $this->faker->numberBetween(1, 10),
            'manage_stock' => $this->faker->boolean(),
            'stock_status' => $this->faker->randomElement(['in_stock', 'out_of_stock']),
            'is_featured' => $this->faker->boolean(),
            'is_active' => $this->faker->boolean(),
            'has_variants' => $this->faker->boolean(),
            'weight' => $this->faker->randomFloat(2, 0.1, 10),
           // 'status' => $this->faker->randomElement(['draft', 'published', 'archived']),
            'meta_title' => $this->faker->sentence(),
            'meta_description' => $this->faker->sentence(),
            'view_count' => $this->faker->numberBetween(0, 1000),
        ];
    }

    public function withImages(int $count = 3): static
    {
        return $this->has(
            ProductImage::factory()
                ->count($count)
                ->sequence(fn ($sequence) => ['sort_order' => $sequence->index, 'is_primary' => $sequence->index === 0]),
            'images'
        );
    }

    public function withVariants(int $count = 3): static
    {
        return $this->afterCreating(function (Product $product) use ($count) {
            $product->update(['has_variants' => true]);
            ProductVariant::factory()
                ->count($count)
                ->create(['product_id' => $product->id]);
        });
    }
}
