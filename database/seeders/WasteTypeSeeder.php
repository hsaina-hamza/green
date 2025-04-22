<?php

namespace Database\Seeders;

use App\Models\WasteType;
use Illuminate\Database\Seeder;

class WasteTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wasteTypes = [
            [
                'name' => 'General Waste',
                'description' => 'Common household and commercial waste that cannot be recycled',
            ],
            [
                'name' => 'Recyclable',
                'description' => 'Materials that can be recycled including paper, plastic, glass, and metal',
            ],
            [
                'name' => 'Organic',
                'description' => 'Biodegradable waste including food scraps and garden waste',
            ],
            [
                'name' => 'Hazardous',
                'description' => 'Dangerous materials requiring special handling and disposal',
            ],
            [
                'name' => 'Electronic',
                'description' => 'Electronic devices and components',
            ],
            [
                'name' => 'Construction',
                'description' => 'Debris and materials from construction and demolition',
            ],
        ];

        foreach ($wasteTypes as $type) {
            WasteType::create($type);
        }
    }
}
