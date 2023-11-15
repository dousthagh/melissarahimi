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
        Schema::create('lesson_sample_works', function (Blueprint $table) {
            $table->id();
            $table->integer('lesson_id')->index();
            $table->integer('user_level_category_id')->index();
            $table->text('description')->nullable();
            $table->text('master_description')->nullable();
            $table->text('file_path');
            $table->text('thumbnail_path');
            $table->enum('status', ['new', 'accepted', 'rejected'])->default('new');
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
        Schema::dropIfExists('lesson_sample_works');
    }
};
