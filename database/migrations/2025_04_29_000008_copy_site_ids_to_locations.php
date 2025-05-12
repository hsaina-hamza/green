<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First add site_id column to locations if it doesn't exist
        if (!Schema::hasColumn('locations', 'site_id')) {
            Schema::table('locations', function (Blueprint $table) {
                $table->foreignId('site_id')->nullable()->after('id')->constrained()->onDelete('cascade');
            });
        }

        // Copy site_ids from waste_reports to locations
        $locations = DB::table('locations')->get();
        foreach ($locations as $location) {
            // Get the most recent waste report for this location
            $wasteReport = DB::table('waste_reports')
                ->where('location_id', $location->id)
                ->whereNotNull('site_id')
                ->orderBy('created_at', 'desc')
                ->first();

            if ($wasteReport) {
                DB::table('locations')
                    ->where('id', $location->id)
                    ->update(['site_id' => $wasteReport->site_id]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('locations', 'site_id')) {
            Schema::table('locations', function (Blueprint $table) {
                $table->dropForeign(['site_id']);
                $table->dropColumn('site_id');
            });
        }
    }
};
