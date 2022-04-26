<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactPageQueriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_page_queries', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("email");
            $table->string("subject")->nullable();
            $table->text("message");
            $table->text("phone")->nullable();
            $table->string("company")->nullable();
            $table->enum("type", ['contact', 'chatbot'])->default('contact');
            $table->enum("status", ['pending', 'inprocess', 'resolved'])->default('pending');
            $table->text('comments')->nullable();
            $table->unsignedBigInteger("resolved_by")->nullable();
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
        Schema::dropIfExists('contact_page_queries');
    }
}
