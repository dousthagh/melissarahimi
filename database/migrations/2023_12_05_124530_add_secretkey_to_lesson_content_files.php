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
        Schema::table('lesson_content_files', function (Blueprint $table) {
            Schema::table('lesson_content_files', function (Blueprint $table) {
                $table->string('secret_key'); // Adding the new field 'secretkey'
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lesson_content_files', function (Blueprint $table) {
            Schema::table('lesson_content_files', function (Blueprint $table) {
                $table->dropColumn('secret_key'); // Dropping the 'secretkey' field
            });
        });
    }
};
