<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaLibraryFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_library_files', function (Blueprint $table) {
            $table->id();
            $table->string('file');
            $table->boolean('featured')->default(0);
            $table->unsignedBigInteger('media_library_id');
            $table->timestamps();

            $table->foreign('media_library_id')->references('id')->on('media_libraries')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_library_files');
    }
}
