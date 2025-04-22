<?php

namespace Database\Seeders;

use App\Models\Site;
use App\Models\User;
<<<<<<< HEAD
use App\Models\Location;
use App\Models\WasteType;
=======
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808
use App\Models\WasteReport;
use Illuminate\Database\Seeder;

class WasteReportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< HEAD
        // Get required models
        $sites = Site::all();
        $users = User::all();
        $workers = User::where('role', 'worker')->get();
        $locations = Location::all();
        $wasteTypes = WasteType::all();
=======
        // Get some sites and users to associate with reports
        $sites = Site::all();
        $users = User::all();
        $workers = User::where('role', 'worker')->get();
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808

        if ($sites->isEmpty()) {
            $this->command->info('No sites found. Please run SiteSeeder first.');
            return;
        }

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

<<<<<<< HEAD
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
=======
        // Create some sample waste reports
        $statuses = ['pending', 'in_progress', 'completed'];
        $types = ['general', 'recyclable', 'hazardous', 'organic'];
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808
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
<<<<<<< HEAD
                    'urgency_level' => $urgencyLevels[array_rand($urgencyLevels)],
                    'status' => $status,
                    'site_id' => $site->id,
                    'location_id' => $locations->random()->id,
                    'waste_type_id' => $wasteTypes->random()->id,
                    'user_id' => $users->random()->id,
                    'assigned_worker_id' => $worker ? $worker->id : null,
                    'estimated_size' => rand(1, 100),
                    'location_details' => "Near the {$site->name} main entrance",
                    'image_url' => null,
=======
                    'type' => $types[array_rand($types)],
                    'urgency_level' => $urgencyLevels[array_rand($urgencyLevels)],
                    'status' => $status,
                    'site_id' => $site->id,
                    'user_id' => $users->random()->id,
                    'worker_id' => $worker ? $worker->id : null,
                    'estimated_size' => rand(1, 100),
                    'location_details' => "Near the {$site->name} main entrance",
                    'image_url' => null, // You might want to add sample images
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808
                    'created_at' => now()->subDays(rand(1, 30)),
                    'updated_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }
    }
}
