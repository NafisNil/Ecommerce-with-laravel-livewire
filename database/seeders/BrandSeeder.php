<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Brand;
class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        //
        $brands = ['Nike', 'Adidas', 'Puma', 'Reebok', 'Under Armour', 'New Balance', 'Asics', 'Converse', 'Vans', 'Fila'];
        foreach ($brands as $index => $brandName) {
            Brand::create([
                'name' => $brandName,
                'slug' => Str::slug($brandName),
                'description' => "Description for {$brandName}",
                'logo' => "https://via.placeholder.com/150?text={$brandName}",
                'is_active' => true,
                'website' => "https://www.{$brandName}.com",
                'sort_order' => $index + 1,
            ]);
        }
        $this->command->info('Brands seeded successfully!');
    }
}
