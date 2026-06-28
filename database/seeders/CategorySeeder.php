<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $categories = [
            ['name' => 'Electronics', 'description' => 'Electronic gadgets and devices'],
            ['name' => 'Fashion', 'description' => 'Clothing, shoes, and accessories'],
            ['name' => 'Home & Kitchen', 'description' => 'Appliances, furniture, and home decor'],
            ['name' => 'Sports & Outdoors', 'description' => 'Sporting goods and outdoor equipment'],
            ['name' => 'Beauty & Personal Care', 'description' => 'Cosmetics, skincare, and personal care products'],
            ['name' => 'Toys & Games', 'description' => 'Toys, games, and puzzles for all ages'],
            ['name' => 'Books & Media', 'description' => 'Books, movies, music, and other media'],
            ['name' => 'Health & Wellness', 'description' => 'Health supplements and wellness products'],
            ['name' => 'Automotive', 'description' => 'Car accessories and automotive products'],
            ['name' => 'Pet Supplies', 'description' => 'Products for pets and pet care']
        ];
        foreach ($categories as $index => $category) {
            Category::create([
                'name' => $category['name'],
                'slug' => Str::slug($category['name']),
                'description' => $category['description'],
                'is_active' => true,
                'sort_order' => $index + 1,
                'meta_title' => $category['name'] . ' - Buy Online',
                'meta_description' => 'Find the best deals on ' . $category['name']
            ]);
        }
        $this->command->info('Categories seeded successfully!');
    }
}
