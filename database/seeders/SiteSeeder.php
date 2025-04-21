<?php

namespace Database\Seeders;

use App\Models\Site;
use Illuminate\Database\Seeder;

class SiteSeeder extends Seeder
{
    public function run()
    {
        // Create some predefined sites with realistic coordinates
        $sites = [
            [
                'name' => 'Central Park Waste Station',
                'latitude' => 40.785091,
                'longitude' => -73.968285,
            ],
            [
                'name' => 'Downtown Transfer Station',
                'latitude' => 40.712776,
                'longitude' => -74.005974,
            ],
            [
                'name' => 'East Side Collection Center',
                'latitude' => 40.768479,
                'longitude' => -73.956298,
            ],
            [
                'name' => 'West Side Processing Facility',
                'latitude' => 40.756687,
                'longitude' => -73.989677,
            ],
            [
                'name' => 'North District Collection Point',
                'latitude' => 40.796225,
                'longitude' => -73.949997,
            ],
            [
                'name' => 'South District Recycling Center',
                'latitude' => 40.728224,
                'longitude' => -73.994766,
            ],
            [
                'name' => 'Riverside Waste Management',
                'latitude' => 40.775882,
                'longitude' => -73.976797,
            ],
            [
                'name' => 'Harbor Transfer Station',
                'latitude' => 40.704586,
                'longitude' => -74.016835,
            ],
            [
                'name' => 'Midtown Collection Center',
                'latitude' => 40.754932,
                'longitude' => -73.984016,
            ],
            [
                'name' => 'Queens Recycling Facility',
                'latitude' => 40.744679,
                'longitude' => -73.948542,
            ],
        ];

        foreach ($sites as $site) {
            Site::create($site);
        }

        // Create additional random sites
        Site::factory()
            ->count(15)
            ->create();
    }
}
