<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCreatedByModifiedByColumnsToMultipleTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->default(0)->after('name');
            $table->unsignedBigInteger('modified_by')->default(0)->after('created_by');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->default(0)->after('submenu_json');
            $table->unsignedBigInteger('modified_by')->default(0)->after('created_by');
        });

        Schema::table('document_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->default(0)->after('name');
            $table->unsignedBigInteger('modified_by')->default(0)->after('created_by');
        });

        Schema::table('faq_categories', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->default(0)->after('name');
            $table->unsignedBigInteger('modified_by')->default(0)->after('created_by');
        });

        Schema::table('static_blocks', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->default(0)->after('contents');
            $table->unsignedBigInteger('modified_by')->default(0)->after('created_by');
        });

        Schema::table('media_libraries', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->default(0)->after('directory');
            $table->unsignedBigInteger('modified_by')->default(0)->after('created_by');
        });

        Schema::table('media_library_files', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->default(0)->after('media_library_id');
            $table->unsignedBigInteger('modified_by')->default(0)->after('created_by');
        });

        Schema::table('slider_images', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->default(0)->after('image');
            $table->unsignedBigInteger('modified_by')->default(0)->after('created_by');
        });

        Schema::table('slider_settings', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->default(0)->after('speed');
            $table->unsignedBigInteger('modified_by')->default(0)->after('created_by');
        });

        Schema::table('managers', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->default(0)->after('image');
            $table->unsignedBigInteger('modified_by')->default(0)->after('created_by');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->default(0)->after('manager_id');
            $table->unsignedBigInteger('modified_by')->default(0)->after('created_by');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->unsignedBigInteger('created_by')->default(0)->after('attachment');
            $table->unsignedBigInteger('modified_by')->default(0)->after('created_by');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('modified_by');
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('modified_by');
        });

        Schema::table('document_categories', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('modified_by');
        });

        Schema::table('faq_categories', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('modified_by');
        });

        Schema::table('static_blocks', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('modified_by');
        });

        Schema::table('media_libraries', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('modified_by');
        });

        Schema::table('media_library_files', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('modified_by');
        });

        Schema::table('slider_images', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('modified_by');
        });

        Schema::table('slider_settings', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('modified_by');
        });

        Schema::table('managers', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('modified_by');
        });

        Schema::table('team_members', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('modified_by');
        });

        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn('created_by');
            $table->dropColumn('modified_by');
        });
    }
}
