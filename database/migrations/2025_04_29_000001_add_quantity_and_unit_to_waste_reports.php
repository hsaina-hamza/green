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
            $table->decimal('quantity', 10, 2)->after('waste_type_id');
            $table->string('unit')->after('quantity');
            // Drop estimated_size as it's being replaced by quantity and unit
            $table->dropColumn('estimated_size');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('waste_reports', function (Blueprint $table) {
            $table->integer('estimated_size')->nullable();
            $table->dropColumn(['quantity', 'unit']);
        });
    }
};
