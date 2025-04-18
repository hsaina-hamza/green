<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Database\Seeder;

class WasteReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some sites and users to associate with reports
        $sites = Site::all();
        $users = User::all();
        $workers = User::where('role', 'worker')->get();

        if ($sites->isEmpty()) {
            $this->command->info('No sites found. Please run SiteSeeder first.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        // Create some sample waste reports
        $statuses = ['pending', 'in_progress', 'completed'];
        $types = ['general', 'recyclable', 'hazardous', 'organic'];
        $urgencyLevels = ['low', 'medium', 'high'];

        foreach ($sites as $site) {
            // Create 3-5 reports per site
            $numReports = rand(3, 5);
            
            for ($i = 0; $i < $numReports; $i++) {
                $status = $statuses[array_rand($statuses)];
                $worker = ($status !== 'pending') ? $workers->random() : null;
                
                WasteReport::create([
                    'title' => "Waste Report at {$site->name} #" . ($i + 1),
                    'description' => "Sample waste report for {$site->name}. This is a detailed description of the waste situation that needs attention.",
                    'type' => $types[array_rand($types)],
                    'urgency_level' => $urgencyLevels[array_rand($urgencyLevels)],
                    'status' => $status,
                    'site_id' => $site->id,
                    'user_id' => $users->random()->id,
                    'worker_id' => $worker ? $worker->id : null,
                    'estimated_size' => rand(1, 100),
                    'location_details' => "Near the {$site->name} main entrance",
                    'image_url' => null, // You might want to add sample images
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }
    }
}
