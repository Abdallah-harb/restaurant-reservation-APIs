<?php

namespace Database\Seeders;

use App\Models\Table;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class TableDBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('tables')->truncate();
        Schema::enableForeignKeyConstraints();

        Table::insert([
            [
                'capacity' => 4,
            ],
            [
                'capacity' =>6,
            ],
            [
                'capacity' => 8,
            ],
            [
                'capacity' => 10,
            ]
        ]);
    }
}
