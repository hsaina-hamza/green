<?php

namespace Database\Seeders;

use App\Models\WasteType;
use Illuminate\Database\Seeder;

class MarocWasteTypeSeeder extends Seeder
{
    public function run()
    {
        $wasteTypes = [
            [
                'name' => 'نفايات منزلية',
                'description' => 'النفايات المنزلية العامة من المنازل والمباني السكنية',
                'color' => '#FF4444',
                'icon' => 'fa-trash',
            ],
            [
                'name' => 'نفايات البناء',
                'description' => 'مخلفات البناء والهدم ومواد البناء',
                'color' => '#FFA500',
                'icon' => 'fa-building',
            ],
            [
                'name' => 'نفايات خضراء',
                'description' => 'نفايات الحدائق والمناطق الخضراء والأشجار',
                'color' => '#4CAF50',
                'icon' => 'fa-leaf',
            ],
            [
                'name' => 'نفايات تجارية',
                'description' => 'النفايات من المحلات التجارية والأسواق',
                'color' => '#2196F3',
                'icon' => 'fa-store',
            ],
            [
                'name' => 'نفايات طبية',
                'description' => 'النفايات من المراكز الصحية والمستشفيات',
                'color' => '#E91E63',
                'icon' => 'fa-hospital',
            ],
            [
                'name' => 'نفايات صناعية',
                'description' => 'النفايات من المصانع والمناطق الصناعية',
                'color' => '#9C27B0',
                'icon' => 'fa-industry',
            ],
            [
                'name' => 'نفايات إلكترونية',
                'description' => 'الأجهزة الإلكترونية والكهربائية التالفة',
                'color' => '#607D8B',
                'icon' => 'fa-laptop',
            ],
            [
                'name' => 'نفايات بلاستيكية',
                'description' => 'النفايات البلاستيكية والأكياس',
                'color' => '#795548',
                'icon' => 'fa-wine-bottle',
            ],
        ];

        foreach ($wasteTypes as $wasteType) {
            WasteType::create($wasteType);
        }
    }
}
