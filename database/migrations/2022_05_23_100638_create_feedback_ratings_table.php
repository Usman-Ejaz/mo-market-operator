<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbackRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('feedback_ratings', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('rating');
            $table->string('feedback')->nullable();
            $table->foreignId('chatbot_initiator_id')->constrained('chatbot_initiators')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('feedback_ratings');
    }
}
