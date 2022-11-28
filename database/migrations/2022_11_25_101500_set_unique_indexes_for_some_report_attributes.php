<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetUniqueIndexesForSomeReportAttributes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('report_categories', function (Blueprint $table) {
            $table->unique('name');
        });

        Schema::table('report_attribute_types', function (Blueprint $table) {
            $table->unique('name');
        });

        Schema::table('report_sub_categories', function (Blueprint $table) {
            $table->unique(['name', 'report_category_id']);
        });

        Schema::table('report_attributes', function (Blueprint $table) {
            $table->unique(['name', 'report_sub_category_id']);
        });

        // Schema::table('report_attribute_values', function (Blueprint $table) {
        //     $table->unique(['report_id', 'report_attribute_id']);
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('report_categories', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('report_attribute_types', function (Blueprint $table) {
            $table->dropUnique(['name']);
        });

        Schema::table('report_sub_categories', function (Blueprint $table) {
            $table->dropUnique(['name', 'report_category_id']);
        });

        Schema::table('report_attributes', function (Blueprint $table) {
            $table->dropUnique(['name', 'report_sub_category_id']);
        });

        // Schema::table('report_attribute_values', function (Blueprint $table) {
        //     $table->dropUnique(['report_id', 'report_attribute_id']);
        // });
    }
}
