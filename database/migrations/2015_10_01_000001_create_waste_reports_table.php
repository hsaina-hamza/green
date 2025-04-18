<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWasteReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('waste_reports', function (Blueprint $table) {
            // $table->id();
            // $table->unsignedBigInteger('user_id');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            // $table->unsignedBigInteger('location_id');
            // $table->foreign('location_id')->references('id')->on('locations')->onDelete('cascade');
            // $table->enum('waste_type', ['plastic', 'paper', 'glass', 'organic', 'other']);
            // $table->text('description');
            // $table->string('image')->nullable();
            // $table->enum('status', ['pending', 'in_progress', 'resolved']);
            // $table->unsignedBigInteger('assigned_worker_id')->nullable();
            // $table->timestamps();
            // $table->text('map');
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('location_id')->nullable();
            $table->enum('waste_type', ['plastic', 'paper', 'glass', 'organic', 'other'])->default('other');
            $table->enum('severity', ['low', 'medium', 'high'])->default('medium');
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'resolved'])->default('pending');
            $table->unsignedBigInteger('assigned_worker_id')->nullable();
            $table->timestamps();

            // العلاقات (مفاتيح خارجية)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
            $table->foreign('assigned_worker_id')->references('id')->on('users')->onDelete('set null');
        
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('waste_reports');
    }
}
