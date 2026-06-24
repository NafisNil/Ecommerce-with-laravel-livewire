<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Customer;
/**
 * @extends Factory<Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'customer_id' => Customer::factory(),
            'street_address' => $this->faker->streetAddress(),
            'street_address_2' => $this->faker->secondaryAddress(),
            'phone' => $this->faker->phoneNumber(),
            'full_name' => $this->faker->name(),
            'city' => $this->faker->city(),
            'state' => $this->faker->state(),
            'postal_code' => $this->faker->postcode(),
            'country' => $this->faker->country(),
            'is_default' => $this->faker->boolean(),
            'type' => $this->faker->randomElement(['home', 'work', 'other']),
        ];
    }

    public function default(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'is_default' => true,
            ];
        });
    }
}
