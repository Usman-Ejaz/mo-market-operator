<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnDataTypeOfPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('title', 255)->change();
            $table->string('slug', 255)->change();
        });

        Schema::table('job_posts', function (Blueprint $table) {
            $table->string('title', 255)->change();
            $table->string('slug', 255)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->string('title', 100)->change();
            $table->string('slug', 100)->change();
        });

        Schema::table('job_posts', function (Blueprint $table) {
            $table->string('title', 100)->change();
            $table->string('slug', 150)->change();
        });
    }
}
