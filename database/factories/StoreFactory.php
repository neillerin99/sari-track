<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->randomElement([
                'Aling Nena Sari-Sari Store',
                'Mang Juan Mini Mart',
                'Barangay Corner Store',
                'Tindahan ni Aling Rosa',
                '24/7 Sari-Sari',
                'Pamilya Santos Store',
                'Balay-Balay Sari-Sari',
                'Looban Convenience',
                'Talipapa Express',
                'Neighborhood Sari-Sari'
            ]),
            'user_id' => User::inRandomOrder()->first()->id,
            'baranggay' => $this->faker->words(2, true),
            'city' => $this->faker->words(2, true),
            'province' => $this->faker->words(2, true),
        ];
    }
}
