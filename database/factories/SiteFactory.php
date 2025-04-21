<?php

namespace Database\Factories;

use App\Models\Site;
use Illuminate\Database\Eloquent\Factories\Factory;

class SiteFactory extends Factory
{
    protected $model = Site::class;

    public function definition(): array
    {
        // Generate random coordinates within New York City area
        $nyc = [
            'lat' => [40.4774, 40.9176], // min, max latitude
            'lng' => [-74.2591, -73.7004], // min, max longitude
        ];

        return [
            'name' => fake()->randomElement([
                'District Waste Center',
                'Community Collection Point',
                'Municipal Transfer Station',
                'Recycling Facility',
                'Waste Processing Center',
                'Environmental Station',
                'Collection Hub',
            ]) . ' ' . fake()->streetName(),
            'latitude' => fake()->randomFloat(6, $nyc['lat'][0], $nyc['lat'][1]),
            'longitude' => fake()->randomFloat(6, $nyc['lng'][0], $nyc['lng'][1]),
        ];
    }

    /**
     * Configure the factory to generate sites in a specific area.
     */
    public function inArea(float $centerLat, float $centerLng, float $radiusKm = 5.0): static
    {
        return $this->state(function (array $attributes) use ($centerLat, $centerLng, $radiusKm) {
            // Convert radius from km to degrees (approximate)
            $radiusLat = $radiusKm / 111.0; // 1 degree latitude = ~111km
            $radiusLng = $radiusKm / (111.0 * cos(deg2rad($centerLat))); // Adjust for latitude

            return [
                'latitude' => fake()->randomFloat(6, $centerLat - $radiusLat, $centerLat + $radiusLat),
                'longitude' => fake()->randomFloat(6, $centerLng - $radiusLng, $centerLng + $radiusLng),
            ];
        });
    }
}
