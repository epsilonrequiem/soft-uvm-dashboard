<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class YearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('years')->insert([
            [
                'id' => 1,
                'year' => '2024',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'year' => '2025',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'year' => '2026',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
