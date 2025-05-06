<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('waste_reports', function (Blueprint $table) {
            // Add waste_type_id column if it doesn't exist
            if (!Schema::hasColumn('waste_reports', 'waste_type_id')) {
                $table->foreignId('waste_type_id')->nullable()->constrained()->after('type');
            }

            // Add location_id column if it doesn't exist
            if (!Schema::hasColumn('waste_reports', 'location_id')) {
                $table->foreignId('location_id')->nullable()->constrained()->after('site_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste_reports', function (Blueprint $table) {
            if (Schema::hasColumn('waste_reports', 'waste_type_id')) {
                $table->dropForeign(['waste_type_id']);
                $table->dropColumn('waste_type_id');
            }

            if (Schema::hasColumn('waste_reports', 'location_id')) {
                $table->dropForeign(['location_id']);
                $table->dropColumn('location_id');
            }
        });
    }
};
