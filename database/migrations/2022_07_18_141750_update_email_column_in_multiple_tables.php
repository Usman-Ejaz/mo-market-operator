<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateEmailColumnInMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropUnique('applications_email_unique');
        });

        Schema::table('client_details', function (Blueprint $table) {
            $table->dropUnique('client_details_email_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('email')->unique()->change();
        });

        Schema::table('client_details', function (Blueprint $table) {
            $table->string('email')->unique()->change();
        });
    }
}
