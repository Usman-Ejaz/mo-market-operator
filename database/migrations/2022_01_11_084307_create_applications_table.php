<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApplicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applications', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->text('gender')->nullable();
            $table->text('phone')->nullable();
            $table->text('address')->nullable();
            $table->text('city')->nullable();
            $table->text('experience')->nullable();
            $table->text('degree_level')->nullable();
            $table->text('degree_title')->nullable();
            $table->string('resume')->nullable();
            $table->integer('job_id')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('applications');
    }
}
