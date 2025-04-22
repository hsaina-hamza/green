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
            ['name' => 'North District', 'address' => '123 North Street'],
            ['name' => 'South District', 'address' => '456 South Avenue'],
            ['name' => 'East District', 'address' => '789 East Boulevard'],
            ['name' => 'West District', 'address' => '321 West Road'],
            ['name' => 'Central District', 'address' => '654 Central Square'],
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
