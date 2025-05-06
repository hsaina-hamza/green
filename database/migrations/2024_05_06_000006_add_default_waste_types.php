<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\WasteType;

return new class extends Migration
{
    public function up(): void
    {
        $types = [
            [
                'name' => 'Household Waste',
                'description' => 'General household waste and garbage'
            ],
            [
                'name' => 'Recyclable Materials',
                'description' => 'Materials that can be recycled'
            ],
            [
                'name' => 'Organic Waste',
                'description' => 'Biodegradable waste from food and plants'
            ],
            [
                'name' => 'Construction Debris',
                'description' => 'Waste from construction and demolition'
            ],
            [
                'name' => 'Hazardous Waste',
                'description' => 'Dangerous or toxic materials'
            ]
        ];

        foreach ($types as $type) {
            WasteType::firstOrCreate(
                ['name' => $type['name']],
                ['description' => $type['description']]
            );
        }
    }

    public function down(): void
    {
        WasteType::whereIn('name', [
            'Household Waste',
            'Recyclable Materials',
            'Organic Waste',
            'Construction Debris',
            'Hazardous Waste'
        ])->delete();
    }
};
