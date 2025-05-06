<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Site;

return new class extends Migration
{
    public function up(): void
    {
        // Create default site if none exists
        if (Site::count() === 0) {
            Site::create([
                'name' => 'Default Site',
                'description' => 'Default site for public waste reports',
                'address' => 'Morocco',
                'latitude' => 31.7917,  // Morocco's approximate center latitude
                'longitude' => -7.0926, // Morocco's approximate center longitude
            ]);
        }
    }

    public function down(): void
    {
        Site::where('name', 'Default Site')->delete();
    }
};
