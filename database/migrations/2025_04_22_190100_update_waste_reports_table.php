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
            // Add new columns
            $table->foreignId('location_id')->after('site_id')->constrained()->onDelete('cascade');
            $table->foreignId('waste_type_id')->after('location_id')->constrained()->onDelete('cascade');
            
            // Rename worker_id to assigned_worker_id for consistency
            $table->renameColumn('worker_id', 'assigned_worker_id');
            
            // Update status to use enum
            $table->string('status')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste_reports', function (Blueprint $table) {
            $table->dropForeign(['location_id']);
            $table->dropForeign(['waste_type_id']);
            $table->dropColumn(['location_id', 'waste_type_id']);
            $table->renameColumn('assigned_worker_id', 'worker_id');
        });
    }
};
