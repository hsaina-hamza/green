<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('waste_reports', function (Blueprint $table) {
            if (!Schema::hasColumn('waste_reports', 'reported_by')) {
                $table->foreignId('reported_by')->nullable()->constrained('users')->after('description');
            }
        });
    }

    public function down(): void
    {
        Schema::table('waste_reports', function (Blueprint $table) {
            if (Schema::hasColumn('waste_reports', 'reported_by')) {
                $table->dropForeign(['reported_by']);
                $table->dropColumn('reported_by');
            }
        });
    }
};
