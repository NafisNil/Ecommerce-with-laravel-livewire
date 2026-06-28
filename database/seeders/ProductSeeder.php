<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Brand;
class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();
        $brands = Brand::all();

        $this->command->info('Seeding products...');

        for ($i = 1; $i <= 50; $i++) {
            $category = $categories->random();
            $brand = $brands->random();
            $hasVariants = $i % 3 === 0;

            $product = Product::factory()
                ->withImages(rand(2, 5))
                ->create([
                    'category_id' => $category->id,
                    'brand_id' => $brand->id,
                    'has_variants' => $hasVariants,
                ]);

            if ($hasVariants) {
                \App\Models\ProductVariant::factory()
                    ->count(rand(2, 4))
                    ->create(['product_id' => $product->id]);
            }
        }

        $this->command->info('Products seeded successfully.');
    }
}
