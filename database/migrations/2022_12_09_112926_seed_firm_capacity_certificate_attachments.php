<?php

use App\Models\ReportAttributeType;
use App\Models\ReportCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedFirmCapacityCertificateAttachments extends Migration
{
    private $catFCC;
    private $subCatAllFCC;
    private $subCatContractedFCC;
    private $subCatFCDemandSupply;
    public function __construct()
    {
        $this->catFCC = ReportCategory::firstWhere('name', "Firm Capacity Certificate");
        $this->subCatAllFCC = $this->catFCC->subCategories()->firstWhere('name', 'All FCC (Generator)');
        $this->subCatContractedFCC = $this->catFCC->subCategories()->firstWhere('name', 'Contracted FCC (Generator)');
        $this->subCatFCDemandSupply = $this->catFCC->subCategories()->firstWhere('name', 'FC (Demand/Supplier)');
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

        $this->subCatAllFCC->attributes()->createMany([
            [
                'name' => 'Certificate',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ],

            [
                'name' => 'Calculation',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ],
        ]);

        $this->subCatContractedFCC->attributes()->createMany([
            [
                'name' => 'Certificate',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ],

            [
                'name' => 'Calculation',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ],
        ]);

        $this->subCatFCDemandSupply->attributes()->createMany([
            [
                'name' => 'Certificate',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ],

            [
                'name' => 'Calculation',
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
        $this->subCatAllFCC->attributes()->whereIn('name', ['Certificate', 'Calculation'])->delete();
        $this->subCatContractedFCC->attributes()->whereIn('name', ['Certificate', 'Calculation'])->delete();
        $this->subCatFCDemandSupply->attributes()->whereIn('name', ['Certificate', 'Calculation'])->delete();
    }
}
