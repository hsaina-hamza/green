<?php

namespace App\Services;

class ConservationTipsService
{
    /**
     * Get conservation tips based on user role.
     *
     * @param string $role
     * @return array
     */
    public function getTips(string $role): array
    {
        $baseTips = [
            [
                'title' => 'Reduce Waste',
                'description' => 'Choose products with minimal packaging and bring your own reusable bags when shopping.',
            ],
            [
                'title' => 'Reuse Materials',
                'description' => 'Find creative ways to reuse items before recycling them.',
            ],
            [
                'title' => 'Recycle Properly',
                'description' => 'Learn which materials can be recycled in your area and sort them correctly.',
            ],
            [
                'title' => 'Compost Organic Waste',
                'description' => 'Start composting food scraps and yard waste to reduce landfill waste.',
            ],
            [
                'title' => 'Save Energy',
                'description' => 'Turn off lights and unplug electronics when not in use.',
            ]
        ];

        $roleTips = [
            'admin' => [
                'title' => 'Monitor Waste Metrics',
                'description' => 'Track and analyze waste management data to identify areas for improvement.',
            ],
            'worker' => [
                'title' => 'Efficient Collection',
                'description' => 'Optimize collection routes and ensure proper waste segregation during pickup.',
            ],
            'user' => [
                'title' => 'Community Involvement',
                'description' => 'Join local cleanup events and encourage neighbors to practice waste reduction.',
            ]
        ];

        return array_merge($baseTips, [$roleTips[$role] ?? $roleTips['user']]);
    }
}
