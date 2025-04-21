<?php

namespace Database\Seeders;

use App\Models\NotificationPreference;
use App\Models\User;
use Illuminate\Database\Seeder;

class NotificationPreferenceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'general',
            'waste_reports',
            'assignments',
            'comments',
            'schedules',
        ];

        User::all()->each(function ($user) use ($categories) {
            foreach ($categories as $category) {
                NotificationPreference::create([
                    'user_id' => $user->id,
                    'category' => $category,
                    'email' => true,
                    'sms' => $user->role === 'worker', // Enable SMS by default for workers
                ]);
            }
        });
    }
}
