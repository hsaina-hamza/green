<?php

namespace Database\Factories;

use App\Models\WasteReport;
use App\Models\User;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

class WasteReportFactory extends Factory
{
    protected $model = WasteReport::class;

    public function definition(): array
    {
        $wasteTypes = ['household', 'construction', 'green_waste', 'electronic', 'hazardous', 'recyclable'];
        $status = fake()->randomElement(['pending', 'in_progress', 'completed']);
        
        $descriptions = [
            'household' => [
                'Household waste accumulation requiring regular pickup.',
                'Mixed household waste including packaging and organic materials.',
                'Residential waste collection needed for apartment complex.',
            ],
            'construction' => [
                'Construction debris from renovation project.',
                'Building materials and demolition waste requiring disposal.',
                'Mixed construction waste including wood, metal, and concrete.',
            ],
            'green_waste' => [
                'Garden waste and tree trimmings for composting.',
                'Landscaping debris requiring collection.',
                'Organic waste from park maintenance.',
            ],
            'electronic' => [
                'Old electronics and appliances for proper disposal.',
                'E-waste collection including computers and peripherals.',
                'Electronic equipment requiring specialized handling.',
            ],
            'hazardous' => [
                'Chemical waste requiring special handling procedures.',
                'Hazardous materials needing proper disposal.',
                'Industrial chemicals and contaminated materials.',
            ],
            'recyclable' => [
                'Recyclable materials including paper, plastic, and metal.',
                'Sorted recyclables ready for collection.',
                'Mixed recyclables from commercial area.',
            ],
        ];

        $wasteType = fake()->randomElement($wasteTypes);

        return [
            'user_id' => User::factory(),
            'site_id' => Site::factory(),
            'waste_type' => $wasteType,
            'description' => fake()->randomElement($descriptions[$wasteType]),
            'status' => $status,
            'assigned_worker_id' => $status !== 'pending' ? 
                User::factory()->worker() : 
                null,
            'created_at' => fake()->dateTimeBetween('-3 months', 'now'),
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
            'assigned_worker_id' => null,
        ]);
    }

    public function inProgress(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'in_progress',
            'assigned_worker_id' => User::factory()->worker(),
        ]);
    }

    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'assigned_worker_id' => User::factory()->worker(),
        ]);
    }

    public function withImage(): static
    {
        return $this->state(fn (array $attributes) => [
            'image_path' => 'waste-reports/default-image.jpg',
        ]);
    }
}
