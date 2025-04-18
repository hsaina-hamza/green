<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('garbage_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_id')->constrained()->onDelete('cascade');
            $table->string('truck_number');
            $table->dateTime('scheduled_time');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('garbage_schedules');
    }
};
