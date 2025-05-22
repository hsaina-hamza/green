<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Seeder;

class MarocLocationSeeder extends Seeder
{
    public function run()
    {
        $locations = [
            [
                'name' => 'حي الوحدة Bloc D',
                'address' => 'حي الوحدة Bloc D، كلميم',
                'latitude' => 28.9878,
                'longitude' => -10.0569,
            ],
            [
                'name' => 'المركز الإداري',
                'address' => 'شارع محمد الخامس، كلميم',
                'latitude' => 28.9865,
                'longitude' => -10.0575,
            ],
            [
                'name' => 'السوق المركزي',
                'address' => 'وسط المدينة، كلميم',
                'latitude' => 28.9870,
                'longitude' => -10.0580,
            ]
        ];

        foreach ($locations as $location) {
            Location::create($location);
        }
    }
}
