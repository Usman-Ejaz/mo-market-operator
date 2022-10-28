<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMODataFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_o_data_files', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_path', '255');
            $table->foreignId('m_o_data_id')->constrained();
            // $table->unsignedBigInteger('m_o_datas_id');
            // $table->foreign('m_o_datas_id')->references('id')->on('m_o_datas');
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
        Schema::dropIfExists('m_o_data_files');
    }
}
