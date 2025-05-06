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
        Schema::create('waste_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        // Insert some default waste types
        DB::table('waste_types')->insert([
            [
                'name' => 'Household Waste',
                'description' => 'General household waste and garbage',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Recyclable Materials',
                'description' => 'Paper, plastic, glass, and metal for recycling',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Organic Waste',
                'description' => 'Food waste, yard trimmings, and other biodegradable materials',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Construction Debris',
                'description' => 'Building materials and construction waste',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hazardous Waste',
                'description' => 'Chemical, medical, or other dangerous materials',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('waste_types');
    }
};
