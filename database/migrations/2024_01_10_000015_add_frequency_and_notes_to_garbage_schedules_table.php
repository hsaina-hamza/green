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
        Schema::table('garbage_schedules', function (Blueprint $table) {
            $table->string('frequency')->default('once')->after('scheduled_time');
            $table->text('notes')->nullable()->after('frequency');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('garbage_schedules', function (Blueprint $table) {
            $table->dropColumn(['frequency', 'notes']);
        });
    }
};
