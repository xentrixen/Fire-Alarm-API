<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFireReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('fire_reports', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->decimal('latitude', 9, 6);
            $table->decimal('longitude', 9, 6);
            $table->string('image');
            $table->string('image_id');
            $table->unsignedBigInteger('citizen_id');
            $table->enum('level_of_fire', ['First Alarm', 'Second Alarm', 'Third Alarm', 'General Alarm'])->nullable();
            $table->foreign('citizen_id')->references('id')->on('citizens');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('fire_reports');
    }
}
