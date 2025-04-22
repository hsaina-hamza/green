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
        // No changes needed as the existing table structure is sufficient
        // The original migration already has all needed columns plus softDeletes
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No changes to revert
    }
};
