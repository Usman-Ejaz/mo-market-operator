<?php

use App\Models\ReportAttributeType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileAndNumberTypeToAttributeType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        ReportAttributeType::create([
            'name' => 'file',
        ]);

        ReportAttributeType::create([
            'name' => 'number'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        ReportAttributeType::whereIn('name', ['file', 'number'])->delete();
    }
}
