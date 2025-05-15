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
            if (!Schema::hasColumn('waste_reports', 'urgency_level')) {
                $table->string('urgency_level')->default('normal')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste_reports', function (Blueprint $table) {
            if (Schema::hasColumn('waste_reports', 'urgency_level')) {
                $table->dropColumn('urgency_level');
            }
        });
    }
};
