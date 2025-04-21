<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        $wasteReports = WasteReport::all();

        if ($users->isEmpty()) {
            $this->command->info('No users found. Please run UserSeeder first.');
            return;
        }

        if ($wasteReports->isEmpty()) {
            $this->command->info('No waste reports found. Please run WasteReportSeeder first.');
            return;
        }

        // Sample comments
        $sampleComments = [
            'Thank you for the quick response.',
            'This needs immediate attention.',
            'I will check this location tomorrow.',
            'The situation has improved since last week.',
            'Please provide more details about the location.',
            'I have assigned a team to handle this.',
            'We need additional resources for this task.',
            'The cleanup has been completed successfully.',
            'Regular monitoring is required here.',
            'This is a recurring issue that needs a permanent solution.',
        ];

        // Add 2-4 comments to each waste report
        foreach ($wasteReports as $report) {
            $numComments = rand(2, 4);
            
            for ($i = 0; $i < $numComments; $i++) {
                Comment::create([
                    'user_id' => $users->random()->id,
                    'waste_report_id' => $report->id,
                    'text' => $sampleComments[array_rand($sampleComments)],
                    'created_at' => now()->subDays(rand(0, 30))->subHours(rand(1, 24)),
                    'updated_at' => now()->subDays(rand(0, 30))->subHours(rand(1, 24)),
                ]);
            }
        }
    }
}
