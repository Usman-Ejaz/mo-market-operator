<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReportAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('report_attributes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('report_sub_category_id')->constrained()->onDelete('CASCADE')->onUpdate('CASCADE');
            $table->foreignId('report_attribute_type_id')->constrained()->onDelete('CASCADE')->onUpdate('CASCADE');
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
        Schema::dropIfExists('report_attributes');
    }
}
