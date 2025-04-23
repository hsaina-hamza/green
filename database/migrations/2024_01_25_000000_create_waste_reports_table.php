<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('waste_reports', function (Blueprint $table) {
            $table->id();
            $table->string('waste_type');
            $table->decimal('quantity', 10, 2);
            $table->string('unit');
            $table->string('location');
            $table->text('description')->nullable();
            $table->foreignId('reported_by')->constrained('users');
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending');
            $table->string('image_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('waste_reports');
    }
};
