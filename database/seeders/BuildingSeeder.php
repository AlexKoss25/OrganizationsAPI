<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Building;

class BuildingSeeder extends Seeder
{
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        \DB::table('buildings')->delete();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $buildingsCoordinates = [
            ['address' => 'Москва, ул. Ленина, 1', 'lat' => 55.751, 'lng' => 37.618],
            ['address' => 'Москва, ул. Ленина, 2', 'lat' => 55.752, 'lng' => 37.620],
            ['address' => 'Москва, ул. Ленина, 3', 'lat' => 55.753, 'lng' => 37.622],
            ['address' => 'Москва, ул. Ленина, 4', 'lat' => 55.760, 'lng' => 37.630],
            ['address' => 'Москва, ул. Ленина, 5', 'lat' => 55.770, 'lng' => 37.640],
            ['address' => 'Москва, ул. Ленина, 6', 'lat' => 55.780, 'lng' => 37.650],
            ['address' => 'Москва, ул. Ленина, 7', 'lat' => 55.790, 'lng' => 37.660],
            ['address' => 'Москва, ул. Ленина, 8', 'lat' => 55.800, 'lng' => 37.670],
            ['address' => 'Москва, ул. Ленина, 9', 'lat' => 55.810, 'lng' => 37.680],
            ['address' => 'Москва, ул. Ленина, 10', 'lat' => 55.820, 'lng' => 37.690],
            ['address' => 'Москва, ул. Ленина, 11', 'lat' => 55.830, 'lng' => 37.700],
            ['address' => 'Москва, ул. Ленина, 12', 'lat' => 55.840, 'lng' => 37.710],
        ];

        foreach ($buildingsCoordinates as $coord) {
            Building::create([
                'address' => $coord['address'],
                'latitude' => $coord['lat'],
                'longitude' => $coord['lng'],
            ]);
        }

        $this->command->info('Buildings seeded successfully!');
    }
}
