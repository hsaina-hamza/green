<?php

namespace Database\Seeders;

use App\Models\WasteReport;
use App\Models\User;
use App\Models\Site;
use Illuminate\Database\Seeder;

class WasteReportSeeder extends Seeder
{
    public function run()
    {
        $users = User::where('role', 'user')->get();
        $workers = User::where('role', 'worker')->get();
        $sites = Site::all();
        $wasteTypes = ['household', 'construction', 'green_waste', 'electronic', 'hazardous', 'recyclable'];
        $statuses = ['pending', 'in_progress', 'completed'];

        // Create some reports for each user
        foreach ($users as $user) {
            $numReports = rand(1, 5);
            
            for ($i = 0; $i < $numReports; $i++) {
                $status = $statuses[array_rand($statuses)];
                $assignedWorkerId = $status !== 'pending' ? $workers->random()->id : null;
                
                WasteReport::create([
                    'user_id' => $user->id,
                    'site_id' => $sites->random()->id,
                    'waste_type' => $wasteTypes[array_rand($wasteTypes)],
                    'description' => "Waste report for {$wasteTypes[array_rand($wasteTypes)]} materials requiring disposal. Location details and specific handling instructions provided.",
                    'status' => $status,
                    'assigned_worker_id' => $assignedWorkerId,
                    'created_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }

        // Create some reports with random dates for statistics
        foreach (range(1, 50) as $index) {
            $status = $statuses[array_rand($statuses)];
            $assignedWorkerId = $status !== 'pending' ? $workers->random()->id : null;
            $createdAt = now()->subDays(rand(0, 365));

            WasteReport::create([
                'user_id' => $users->random()->id,
                'site_id' => $sites->random()->id,
                'waste_type' => $wasteTypes[array_rand($wasteTypes)],
                'description' => "Historical waste report requiring attention. Details about waste type and handling requirements included.",
                'status' => $status,
                'assigned_worker_id' => $assignedWorkerId,
                'created_at' => $createdAt,
                'updated_at' => $status !== 'pending' ? $createdAt->addDays(rand(1, 5)) : $createdAt,
            ]);
        }

        // Create some recent pending reports
        foreach (range(1, 10) as $index) {
            WasteReport::create([
                'user_id' => $users->random()->id,
                'site_id' => $sites->random()->id,
                'waste_type' => $wasteTypes[array_rand($wasteTypes)],
                'description' => "Recent waste report pending review and assignment. Immediate attention required.",
                'status' => 'pending',
                'created_at' => now()->subHours(rand(1, 24)),
            ]);
        }
    }
}
