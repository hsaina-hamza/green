<?php

namespace Database\Seeders;

use App\Models\GarbageSchedule;
use App\Models\Site;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class GarbageScheduleSeeder extends Seeder
{
    public function run()
    {
        $sites = Site::all();
        $truckNumbers = ['T-001', 'T-002', 'T-003', 'T-004', 'T-005'];

        // Create past schedules
        foreach ($sites as $site) {
            // 2-4 past schedules per site
            $numPastSchedules = rand(2, 4);
            
            for ($i = 0; $i < $numPastSchedules; $i++) {
                GarbageSchedule::create([
                    'site_id' => $site->id,
                    'truck_number' => $truckNumbers[array_rand($truckNumbers)],
                    'scheduled_time' => Carbon::now()
                        ->subDays(rand(1, 30))
                        ->setHour(rand(6, 18))
                        ->setMinute(0)
                        ->setSecond(0),
                ]);
            }
        }

        // Create upcoming schedules
        foreach ($sites as $site) {
            // 1-3 upcoming schedules per site
            $numUpcomingSchedules = rand(1, 3);
            
            for ($i = 0; $i < $numUpcomingSchedules; $i++) {
                // Schedule between tomorrow and next 14 days
                $scheduledDate = Carbon::now()
                    ->addDays(rand(1, 14))
                    ->setHour(rand(6, 18))
                    ->setMinute(0)
                    ->setSecond(0);

                GarbageSchedule::create([
                    'site_id' => $site->id,
                    'truck_number' => $truckNumbers[array_rand($truckNumbers)],
                    'scheduled_time' => $scheduledDate,
                ]);
            }
        }

        // Create some regular weekly schedules for the next month
        $regularSites = $sites->random(5); // Select 5 random sites for regular scheduling
        
        foreach ($regularSites as $site) {
            $weekday = rand(1, 5); // Monday to Friday
            $hour = rand(6, 18);
            
            // Create 4 weekly schedules
            for ($week = 1; $week <= 4; $week++) {
                $scheduledDate = Carbon::now()
                    ->addWeeks($week)
                    ->startOfWeek()
                    ->addDays($weekday - 1)
                    ->setHour($hour)
                    ->setMinute(0)
                    ->setSecond(0);

                GarbageSchedule::create([
                    'site_id' => $site->id,
                    'truck_number' => $truckNumbers[array_rand($truckNumbers)],
                    'scheduled_time' => $scheduledDate,
                ]);
            }
        }
    }
}
