<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\WasteReport;
use App\Models\User;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    public function run()
    {
        $users = User::all();
        $reports = WasteReport::all();
        
        // Standard comment templates
        $commentTemplates = [
            'worker' => [
                'Will inspect the site tomorrow morning.',
                'Equipment required for this cleanup has been arranged.',
                'This will require special handling due to the waste type.',
                'Scheduled for pickup next week.',
                'Team has been notified about this task.',
                'Additional resources might be needed for this.',
                'Site inspection completed, proceeding with cleanup.',
                'Coordinating with the disposal facility for this.',
            ],
            'user' => [
                'Please handle this as soon as possible.',
                'There\'s more waste accumulated since the initial report.',
                'Thank you for the quick response.',
                'When can we expect this to be cleared?',
                'The situation is becoming urgent.',
                'Additional waste details: mostly packaging materials.',
                'Best time for collection would be morning hours.',
                'Access to the site is available 24/7.',
            ],
            'admin' => [
                'Assigned to the nearest available team.',
                'Priority status updated for this report.',
                'Coordinating with local authorities on this.',
                'Special equipment has been authorized for this task.',
                'Please provide more details about access restrictions.',
                'Environmental assessment team will be involved.',
                'Scheduled for immediate action.',
                'Resources have been allocated for this task.',
            ],
        ];

        // Add comments to each waste report
        foreach ($reports as $report) {
            // Number of comments for this report (1 to 5)
            $numComments = rand(1, 5);
            
            // Create comments with appropriate timestamps
            $commentDate = $report->created_at;
            
            for ($i = 0; $i < $numComments; $i++) {
                $user = $users->random();
                $roleTemplates = $commentTemplates[$user->role] ?? $commentTemplates['user'];
                
                Comment::create([
                    'user_id' => $user->id,
                    'waste_report_id' => $report->id,
                    'text' => $roleTemplates[array_rand($roleTemplates)],
                    'created_at' => $commentDate,
                    'updated_at' => $commentDate,
                ]);
                
                // Add some random hours to the next comment
                $commentDate = $commentDate->addHours(rand(1, 48));
            }
        }

        // Add some recent comments to active reports
        $activeReports = WasteReport::whereIn('status', ['pending', 'in_progress'])->get();
        
        foreach ($activeReports as $report) {
            $user = $users->random();
            $roleTemplates = $commentTemplates[$user->role] ?? $commentTemplates['user'];
            
            Comment::create([
                'user_id' => $user->id,
                'waste_report_id' => $report->id,
                'text' => $roleTemplates[array_rand($roleTemplates)],
                'created_at' => now()->subHours(rand(1, 24)),
            ]);
        }
    }
}
