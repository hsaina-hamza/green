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
            // Ensure all required columns exist
            if (!Schema::hasColumn('waste_reports', 'waste_type_id')) {
                $table->foreignId('waste_type_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('waste_reports', 'location_id')) {
                $table->foreignId('location_id')->nullable()->constrained()->onDelete('set null');
            }
            if (!Schema::hasColumn('waste_reports', 'quantity')) {
                $table->decimal('quantity', 10, 2)->nullable();
            }
            if (!Schema::hasColumn('waste_reports', 'unit')) {
                $table->string('unit')->nullable();
            }
            if (!Schema::hasColumn('waste_reports', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('waste_reports', 'status')) {
                $table->string('status')->default('pending');
            }
            if (!Schema::hasColumn('waste_reports', 'image_path')) {
                $table->string('image_path')->nullable();
            }
            if (!Schema::hasColumn('waste_reports', 'reported_by')) {
                $table->foreignId('reported_by')->nullable()->constrained('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse as we're just ensuring columns exist
    }
};
