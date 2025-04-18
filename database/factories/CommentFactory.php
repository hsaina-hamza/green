<?php

namespace Database\Factories;

use App\Models\Comment;
use App\Models\User;
use App\Models\WasteReport;
use Illuminate\Database\Eloquent\Factories\Factory;

class CommentFactory extends Factory
{
    protected $model = Comment::class;

    public function definition(): array
    {
        $commentTemplates = [
            'admin' => [
                'Assigned to the nearest available team.',
                'Priority status updated for this report.',
                'Coordinating with local authorities.',
                'Special equipment has been authorized.',
                'Environmental assessment team notified.',
                'Resources allocated for immediate action.',
                'Scheduled for priority handling.',
                'Team dispatched for assessment.',
            ],
            'worker' => [
                'Will inspect the site shortly.',
                'Equipment prepared for cleanup.',
                'Special handling procedures required.',
                'Scheduled for collection tomorrow.',
                'Team has been briefed on requirements.',
                'Additional resources requested.',
                'Site inspection completed.',
                'Coordinating with disposal facility.',
            ],
            'user' => [
                'Please address this as soon as possible.',
                'Situation has become more urgent.',
                'Thank you for the quick response.',
                'When will this be cleared?',
                'Additional waste has accumulated.',
                'Access instructions: through main gate.',
                'Best collection time: early morning.',
                'Site is accessible 24/7.',
            ],
        ];

        $user = User::factory()->create();
        $roleTemplates = $commentTemplates[$user->role] ?? $commentTemplates['user'];

        return [
            'user_id' => $user->id,
            'waste_report_id' => WasteReport::factory(),
            'text' => fake()->randomElement($roleTemplates),
            'created_at' => fake()->dateTimeBetween('-1 month', 'now'),
        ];
    }

    public function fromAdmin(): static
    {
        return $this->state(function (array $attributes) {
            $user = User::factory()->admin()->create();
            return [
                'user_id' => $user->id,
                'text' => fake()->randomElement([
                    'Assigned to the nearest available team.',
                    'Priority status updated for this report.',
                    'Coordinating with local authorities.',
                    'Special equipment has been authorized.',
                ]),
            ];
        });
    }

    public function fromWorker(): static
    {
        return $this->state(function (array $attributes) {
            $user = User::factory()->worker()->create();
            return [
                'user_id' => $user->id,
                'text' => fake()->randomElement([
                    'Will inspect the site shortly.',
                    'Equipment prepared for cleanup.',
                    'Special handling procedures required.',
                    'Scheduled for collection tomorrow.',
                ]),
            ];
        });
    }

    public function recent(): static
    {
        return $this->state(fn (array $attributes) => [
            'created_at' => fake()->dateTimeBetween('-24 hours', 'now'),
        ]);
    }
}
