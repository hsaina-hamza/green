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
            RoleSeeder::class,        // Add roles first
            UserSeeder::class,        // Add users second
            SiteSeeder::class,
            LocationSeeder::class,    
            BusTimeSeeder::class,     
            WasteReportSeeder::class,
            GarbageScheduleSeeder::class,
            CommentSeeder::class,
            NotificationPreferenceSeeder::class,
        ]);
    }
}
