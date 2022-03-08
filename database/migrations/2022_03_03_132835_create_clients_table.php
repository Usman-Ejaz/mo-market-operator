<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            // $table->string('email')->unique()->index();
            $table->string('username')->nullable()->unique()->index();
            $table->string('password')->nullable();
            $table->string('type')->index();
            $table->string('image')->nullable();
            $table->text('address');
            $table->string('categories');
            $table->boolean('approved')->default(0)->index();
            $table->string('pri_name');
            $table->string('pri_address');
            $table->string('pri_telephone');
            $table->string('pri_facsimile_telephone');
            $table->string('pri_email')->unique();
            $table->string('pri_signature');
            $table->string('sec_name');
            $table->string('sec_address');
            $table->string('sec_telephone');
            $table->string('sec_facsimile_telephone');
            $table->string('sec_email');
            $table->string('sec_signature');
            $table->timestamp('last_login_at')->nullable();
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
        Schema::dropIfExists('clients');
    }
}
