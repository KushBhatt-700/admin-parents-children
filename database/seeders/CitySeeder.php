<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CitySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('cities')->insert([

            // GUJARAT
            ['id' => 1, 'state_id' => 1, 'name' => 'Ahmedabad'],
            ['id' => 2, 'state_id' => 1, 'name' => 'Surat'],

            // MAHARASHTRA
            ['id' => 3, 'state_id' => 2, 'name' => 'Mumbai'],
            ['id' => 4, 'state_id' => 2, 'name' => 'Pune'],

            // DELHI
            ['id' => 5, 'state_id' => 3, 'name' => 'New Delhi'],

            // CALIFORNIA
            ['id' => 6, 'state_id' => 4, 'name' => 'Los Angeles'],
            ['id' => 7, 'state_id' => 4, 'name' => 'San Francisco'],

            // TEXAS
            ['id' => 8, 'state_id' => 5, 'name' => 'Houston'],
            ['id' => 9, 'state_id' => 5, 'name' => 'Dallas'],
        ]);
    }
}
