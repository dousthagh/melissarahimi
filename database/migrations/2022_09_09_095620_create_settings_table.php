<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->default("Melissa Rahimi PMU Academy");
            $table->string('logo_file_path');
            $table->string('side_image_path');
            $table->string('rules')->default("rules");
            $table->string('about_us')->default("about us");
            $table->string('address')->default("Iran");
            $table->string('latitude')->default("0");
            $table->string('longitude')->default("0");
            $table->string('mobile')->default("0");
            $table->string('tell')->default("0");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('settings');
    }
};
