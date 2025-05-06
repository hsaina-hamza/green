<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Site;

return new class extends Migration
{
    public function up(): void
    {
        // Get or create default site
        $defaultSite = Site::first() ?? Site::create([
            'name' => 'Default Site',
            'description' => 'Default site for public waste reports',
            'address' => 'Morocco',
        ]);

        Schema::table('locations', function (Blueprint $table) use ($defaultSite) {
            // Add site_id column with default value
            $table->foreignId('site_id')->default($defaultSite->id)->after('id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            $table->dropForeign(['site_id']);
            $table->dropColumn('site_id');
        });
    }
};
