<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends Factory<Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name  = fake()->unique()->randomElement(['Electronics', 'Fashion', 'Home & Garden', 'Sports', 'Toys', 'Books', 'Health & Beauty']);
        return [
            //
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(),
            'image' => fake()->imageUrl(),
            'is_active' => fake()->boolean(),
            'meta_title' => fake()->sentence(),
            'meta_description' => fake()->paragraph(),
            'sort_order' => fake()->numberBetween(1, 100),
        ];
    }
}
