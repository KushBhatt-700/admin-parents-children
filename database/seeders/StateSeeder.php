<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('states')->insert([

            // INDIA STATES
            ['id' => 1, 'country_id' => 1, 'name' => 'Gujarat'],
            ['id' => 2, 'country_id' => 1, 'name' => 'Maharashtra'],
            ['id' => 3, 'country_id' => 1, 'name' => 'Delhi'],

            // USA STATES
            ['id' => 4, 'country_id' => 2, 'name' => 'California'],
            ['id' => 5, 'country_id' => 2, 'name' => 'Texas'],

        ]);
    }
}
