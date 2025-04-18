<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Clear storage directory for waste report images
        Storage::disk('public')->deleteDirectory('waste-reports');
        Storage::disk('public')->makeDirectory('waste-reports');

        // Run seeders in order of dependencies
        $this->call([
            UserSeeder::class,      // First create users
            SiteSeeder::class,      // Then create sites
            WasteReportSeeder::class, // Then create waste reports
            CommentSeeder::class,    // Then add comments to reports
            GarbageScheduleSeeder::class, // Finally create schedules
        ]);
    }
}
