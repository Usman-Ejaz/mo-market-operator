<?php

use App\Models\ReportAttributeType;
use App\Models\ReportCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedContractDetailsAttachments extends Migration
{

    private $catContractDetails;
    private $subCatContractDetails;

    public function __construct()
    {
        $this->catContractDetails = ReportCategory::firstWhere('name', "Contract Details");
        $this->subCatContractDetails = $this->catContractDetails->subCategories()->firstWhere('name', 'Contract Details');
    }
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $attributes = ReportAttributeType::all();
        $attributeTypes = [];
        foreach ($attributes as $att) {
            $attributeTypes[$att->name] = $att;
        }

        $this->subCatContractDetails->attributes()->createMany([
            [
                'name' => 'File',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->subCatContractDetails->attributes()->whereIn('name', ['File'])->delete();
    }
}
