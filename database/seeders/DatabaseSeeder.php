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
<<<<<<< HEAD
            LocationSeeder::class,    // Add LocationSeeder first
            BusTimeSeeder::class,     // Then BusTimeSeeder
            WasteTypeSeeder::class,   // Add WasteTypeSeeder before WasteReportSeeder
=======
>>>>>>> 231977c8c8cc7dfc8f6b499ce1a4fff2b8175808
            WasteReportSeeder::class,
            GarbageScheduleSeeder::class,
            CommentSeeder::class,
            NotificationPreferenceSeeder::class,
        ]);
    }
}
