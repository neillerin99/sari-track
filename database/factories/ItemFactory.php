<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Item>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(2, true), // e.g. "Pancit Canton"
            'brand' => $this->faker->randomElement(['Lucky Me', 'Bear Brand', 'Nescafe', 'Generic']),
            'category' => $this->faker->randomElement(['Snacks', 'Drinks', 'Canned Goods', 'Toiletries']),
            'unit' => $this->faker->randomElement(['pcs', 'sachet', 'bottle', 'pack']),
            'barcode' => $this->faker->ean13(),
            'description' => $this->faker->sentence(8),
            'quantity' => $this->faker->numberBetween(0, 100),
            'expiration_date' => $this->faker->optional()->dateTimeBetween('now', '+1 year'),
            'cost_price' => $this->faker->randomFloat(2, 5, 100),
            'selling_price' => function (array $attrs) {
                return $attrs['cost_price'] + rand(2, 20);
            },
            'is_active' => $this->faker->boolean(90), // 90% chance active
        ];
    }
}
