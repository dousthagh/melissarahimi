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
        Schema::table('lesson_sample_works', function (Blueprint $table) {
            $table->text('master_file_path')->nullable();
            $table->text('master_thumbnail_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_sample_works', function (Blueprint $table) {
            $table->dropColumn('master_file_path');
            $table->dropColumn('master_thumbnail_path');
        });
    }
};
