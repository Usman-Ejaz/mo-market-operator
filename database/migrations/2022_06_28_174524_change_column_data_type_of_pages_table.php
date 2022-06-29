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
            $table->string('title', 256)->change();
            $table->string('slug', 256)->change();
        });

        Schema::table('job_posts', function (Blueprint $table) {
            $table->string('title', 256)->change();
            $table->string('slug', 256)->change();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('title', 256)->change();
            $table->string('slug', 256)->change();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->string('identifier', 100)->change();
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
            $table->string('title', 256)->change();
            $table->string('slug', 256)->change();
        });

        Schema::table('job_posts', function (Blueprint $table) {
            $table->string('title', 256)->change();
            $table->string('slug', 256)->change();
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->string('title', 256)->change();
            $table->string('slug', 256)->change();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->string('identifier', 65)->change();
        });
    }
}
