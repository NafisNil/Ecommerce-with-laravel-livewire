<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends Factory<Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->company();
        return [
            //
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => $this->faker->paragraph(),
            'logo' => $this->faker->imageUrl(),
            'is_active' => $this->faker->boolean(),
            'sort_order' => $this->faker->numberBetween(1, 100),
            'website' => $this->faker->url(),
        ];
    }
}
