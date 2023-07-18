<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MealDataBaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('meals')->truncate();
        Schema::enableForeignKeyConstraints();

        Meal::insert([
            [
                'name' => 'Spaghetti Carbonara',
                'price' => 12.99,
                'description' => 'Spaghetti with eggs, bacon, and parmesan cheese.',
                'quantity_available' => 50,
                'discount' => 0
            ],
            [
                'name' => 'Burger and Fries',
                'price' => 10.99,
                'description' => 'Beef burger with lettuce, tomato, and cheese, served with french fries.',
                'quantity_available' => 30,
                'discount' => 5
            ],
            [
                'name' => 'Caesar Salad',
                'price' => 8.99,
                'description' => 'Romaine lettuce, croutons, parmesan cheese, and Caesar dressing.',
                'quantity_available' => 20,
                'discount' => 0
            ]
        ]);

    }
}
