<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeclarationColumnsToClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->string('dec_name')->after('categories')->nullable();
            $table->timestamp('dec_date')->after('dec_name')->nullable();
            $table->string('dec_signature')->after('dec_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('dec_name');
            $table->dropColumn('dec_date');
            $table->dropColumn('dec_signature');
        });
    }
}
