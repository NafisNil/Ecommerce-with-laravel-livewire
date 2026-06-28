<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Customer;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Address;
use App\Models\Review;
use App\Models\Product;
class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $testCustomer = Customer::create([
            'name' => 'Test Customer',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
            'phone' => '1234567890',
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'is_active' => true,
            'email_verified_at' => now(),
            'remember_token' => Str::random(10),
        ]);

        Address::factory()->default()->create([
            'customer_id' => $testCustomer->id,
        ]);

        Address::factory()->create([
            'customer_id' => $testCustomer->id,
        ]);

        $this->command->info('Test customer created with email: ' . $testCustomer->email);
        $bar = $this->command->getOutput()->createProgressBar(10);

        for ($i = 0; $i < 10; $i++) {
            $customer = Customer::factory()->create();
            Address::factory()->default()->create([
                'customer_id' => $customer->id,
            ]);
            if(rand(0,100)>50){
                Address::factory()->create([
                    'customer_id' => $customer->id,
                ]);
            }
            $reviewCount = rand(0, 5);
            for ($j = 0; $j < $reviewCount; $j++) {
                Review::factory()->create([
                    'customer_id' => $customer->id,
                    'product_id' => Product::inRandomOrder()->first()->id,
                ]);
            }
            Address::factory()->create([
                'customer_id' => $customer->id,
            ]);
            $bar->advance();
        }
        $bar->finish();
        $this->command->info(''); // Move to the next line after the progress bar
    }
}
