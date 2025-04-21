<?php

namespace Database\Factories;

use App\Models\GarbageSchedule;
use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class GarbageScheduleFactory extends Factory
{
    protected $model = GarbageSchedule::class;

    public function definition(): array
    {
        $truckNumbers = ['T-001', 'T-002', 'T-003', 'T-004', 'T-005'];

        return [
            'site_id' => Site::factory(),
            'truck_number' => fake()->randomElement($truckNumbers),
            'scheduled_time' => fake()->dateTimeBetween('now', '+2 weeks'),
        ];
    }

    public function past(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'scheduled_time' => fake()->dateTimeBetween('-1 month', '-1 day')
                    ->setTime(rand(6, 18), 0), // Between 6 AM and 6 PM
            ];
        });
    }

    public function upcoming(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'scheduled_time' => fake()->dateTimeBetween('tomorrow', '+2 weeks')
                    ->setTime(rand(6, 18), 0), // Between 6 AM and 6 PM
            ];
        });
    }

    public function today(): static
    {
        return $this->state(function (array $attributes) {
            $hour = rand(6, 18);
            return [
                'scheduled_time' => Carbon::today()->setHour($hour)->setMinute(0),
            ];
        });
    }

    public function weekly(): static
    {
        return $this->state(function (array $attributes) {
            $weekday = rand(1, 5); // Monday to Friday
            $hour = rand(6, 18);
            
            return [
                'scheduled_time' => Carbon::now()
                    ->addWeek()
                    ->startOfWeek()
                    ->addDays($weekday - 1)
                    ->setHour($hour)
                    ->setMinute(0),
            ];
        });
    }

    public function withSpecificTime(Carbon $time): static
    {
        return $this->state(function (array $attributes) use ($time) {
            return [
                'scheduled_time' => $time,
            ];
        });
    }
}
