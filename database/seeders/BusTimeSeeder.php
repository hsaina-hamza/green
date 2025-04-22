<?php

namespace Database\Seeders;

use App\Models\BusTime;
use App\Models\Location;
use Illuminate\Database\Seeder;

class BusTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = Location::all();

        foreach ($locations as $location) {
            // Morning schedule
            BusTime::create([
                'location_id' => $location->id,
                'route' => 'Route ' . $location->name . ' - Morning',
                'departure_time' => '07:00',
                'arrival_time' => '08:30',
                'frequency' => 'Daily',
            ]);

            // Afternoon schedule
            BusTime::create([
                'location_id' => $location->id,
                'route' => 'Route ' . $location->name . ' - Afternoon',
                'departure_time' => '14:00',
                'arrival_time' => '15:30',
                'frequency' => 'Daily',
            ]);

            // Evening schedule
            BusTime::create([
                'location_id' => $location->id,
                'route' => 'Route ' . $location->name . ' - Evening',
                'departure_time' => '18:00',
                'arrival_time' => '19:30',
                'frequency' => 'Daily',
            ]);
        }
    }
}
