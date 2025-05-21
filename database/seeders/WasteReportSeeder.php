<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\User;
use App\Models\Location;
use App\Models\WasteType;
use App\Models\WasteReport;
use Illuminate\Database\Seeder;

class WasteReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get required models
        $sites = Site::all();
        $users = User::all();
        $workers = User::role('worker')->get();
        $locations = Location::all();
        $wasteTypes = WasteType::all();

        if ($sites->isEmpty()) {
            $this->command->info('No sites found. Please run SiteSeeder first.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        if ($locations->isEmpty()) {
            $this->command->info('No locations found. Please run LocationSeeder first.');
            return;
        }

        if ($wasteTypes->isEmpty()) {
            $this->command->info('No waste types found. Please run WasteTypeSeeder first.');
            return;
        }

        // Create some sample waste reports
        $statuses = ['pending', 'in_progress', 'completed'];
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
                    'urgency_level' => $urgencyLevels[array_rand($urgencyLevels)],
                    'status' => $status,
                    'site_id' => $site->id,
                    'location_id' => $locations->random()->id,
                    'waste_type_id' => $wasteTypes->random()->id,
                    'reported_by' => $users->random()->id,
                    'assigned_worker_id' => $worker ? $worker->id : null,
                    'quantity' => rand(1, 100),
                    'unit' => 'cubic_meters',
                    'location_details' => "Near the {$site->name} main entrance",
                    'image_path' => null,
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }
    }
}
