<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $categories = [
            'Snacks',
            'Beverages',
            'Canned Goods',
            'Instant Noodles',
            'Condiments & Spices',
            'Sweets & Candies',
            'Bakery Products',
            'Frozen Goods',
            'Personal Care',
            'Household Items',
            'Load & E-Services',
            'Cigarettes',
            'Baby Products',
            'Pet Food',
            'Miscellaneous',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'description' => null,
                'is_global' => true
            ]);
        }

        Item::factory()->count(10)->create();
    }
}
