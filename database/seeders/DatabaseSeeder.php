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
            RoleSeeder::class,        // Add RoleSeeder first
            UserSeeder::class,
            SiteSeeder::class,
            LocationSeeder::class,    
            BusTimeSeeder::class,     
            WasteTypeSeeder::class,     // Required before WasteReportSeeder
            WasteReportSeeder::class,
            GarbageScheduleSeeder::class,
            CommentSeeder::class,
            NotificationPreferenceSeeder::class,
        ]);
    }
}
