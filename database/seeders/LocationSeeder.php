<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = [
            ['name' => 'North District', 'address' => '123 North Street', 'latitude' => 35.6895, 'longitude' => -5.834],
            ['name' => 'South District', 'address' => '456 South Avenue', 'latitude' => 31.6295, 'longitude' => -7.9811],
            ['name' => 'East District', 'address' => '789 East Boulevard', 'latitude' => 34.0209, 'longitude' => -6.8417],
            ['name' => 'West District', 'address' => '321 West Road', 'latitude' => 33.5731, 'longitude' => -7.5898],
            ['name' => 'Central District', 'address' => '654 Central Square', 'latitude' => 33.9716, 'longitude' => -6.8498],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
