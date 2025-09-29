<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            ActivitySeeder::class,
            BuildingSeeder::class,
            OrganizationSeeder::class,
        ]);
    }
}
