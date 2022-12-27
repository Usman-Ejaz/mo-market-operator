<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComplaintsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('subject');
            $table->text('message');
            $table->enum('status', ['unresolved', 'hold', 'solved'])->default('unresolved');
            $table->foreignId('complaint_department_id')->constrained();
            $table->unsignedBigInteger('complainant_id')->nullable();
            $table->foreign('complainant_id')->references('id')->on('clients')->nullOnDelete()->cascadeOnUpdate();
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
        Schema::dropIfExists('complaints');
    }
}
