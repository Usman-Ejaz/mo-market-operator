<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportAttributeValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_attribute_values', function (Blueprint $table) {
            $table->id();
            $table->foreignId('report_id')->constrained()->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('report_attribute_id')->constrained()->onDelete('RESTRICT')->onUpdate('CASCADE');
            $table->string('value', 255);
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
        Schema::dropIfExists('report_attribute_values');
    }
}
