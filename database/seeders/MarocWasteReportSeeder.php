<?php

namespace Database\Seeders;

use App\Models\WasteReport;
use App\Models\Location;
use App\Models\User;
use App\Models\WasteType;
use Illuminate\Database\Seeder;

class MarocWasteReportSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $locations = Location::all();
        $wasteTypes = WasteType::all();
        $workers = User::role('worker')->get();

        $reports = [
            [
                'title' => 'تراكم النفايات في حي السلام',
                'description' => 'تراكم كبير للنفايات المنزلية بجانب السوق الأسبوعي',
                'status' => 'pending',
                'urgency_level' => 'high',
                'quantity' => 50,
                'unit' => 'كيلوغرام',
            ],
            [
                'title' => 'مخلفات البناء في حي تيرت',
                'description' => 'مخلفات بناء متراكمة تعيق حركة المرور',
                'status' => 'in_progress',
                'urgency_level' => 'medium',
                'quantity' => 200,
                'unit' => 'كيلوغرام',
            ],
            [
                'title' => 'نفايات بلاستيكية في حي الفرح',
                'description' => 'أكياس بلاستيكية متناثرة في الشارع الرئيسي',
                'status' => 'completed',
                'urgency_level' => 'low',
                'quantity' => 30,
                'unit' => 'كيلوغرام',
            ],
            [
                'title' => 'نفايات تجارية قرب السوق',
                'description' => 'تراكم النفايات خلف المحلات التجارية',
                'status' => 'pending',
                'urgency_level' => 'medium',
                'quantity' => 100,
                'unit' => 'كيلوغرام',
            ],
            [
                'title' => 'مخلفات الأشجار في الحديقة العامة',
                'description' => 'أغصان وأوراق متساقطة تحتاج إلى إزالة',
                'status' => 'in_progress',
                'urgency_level' => 'low',
                'quantity' => 150,
                'unit' => 'كيلوغرام',
            ],
        ];

        foreach ($reports as $report) {
            $newReport = new WasteReport($report);
            $newReport->reported_by = $users->random()->id;
            $newReport->location_id = $locations->random()->id;
            $newReport->waste_type_id = $wasteTypes->random()->id;
            
            if ($report['status'] !== 'pending') {
                $newReport->worker_id = $workers->random()->id;
            }
            
            $newReport->save();
        }
    }
}
