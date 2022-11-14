<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeleteConstraintOnMODataFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('m_o_data_files', function (Blueprint $table) {
            $table->dropForeign(['m_o_data_id']);
            $table->foreign('m_o_data_id')->references('id')->on('m_o_datas')->onDelete('CASCADE')->onUpdate('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('m_o_data_files', function (Blueprint $table) {
            $table->dropForeign(['m_o_data_id']);
            $table->foreign('m_o_data_id')->references('id')->on('m_o_datas');
        });
    }
}
