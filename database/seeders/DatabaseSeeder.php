<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            SiteSeeder::class,
            LocationSeeder::class,    // Add LocationSeeder first
            BusTimeSeeder::class,     // Then BusTimeSeeder
            WasteTypeSeeder::class,   // Add WasteTypeSeeder before WasteReportSeeder
            WasteReportSeeder::class,
            GarbageScheduleSeeder::class,
            CommentSeeder::class,
            NotificationPreferenceSeeder::class,
        ]);
    }
}
