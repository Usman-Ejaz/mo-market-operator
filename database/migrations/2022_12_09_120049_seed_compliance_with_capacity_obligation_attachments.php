<?php

use App\Models\ReportAttributeType;
use App\Models\ReportCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedComplianceWithCapacityObligationAttachments extends Migration
{
    private $catComplianceWithCO;
    private $subCatCapacityObligation;
    private $subCatCreditedCapacity;

    public function __construct()
    {
        $this->catComplianceWithCO = ReportCategory::firstWhere('name', 'Compliance With Capacity Obligation');
        $this->subCatCapacityObligation = $this->catComplianceWithCO->subCategories()->firstWhere('name', 'Capacity Obligation');
        $this->subCatCreditedCapacity = $this->catComplianceWithCO->subCategories()->firstWhere('name', 'Credited Capacity');
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

        $this->subCatCapacityObligation->attributes()->createMany([
            [
                'name' => 'Report',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ],
        ]);

        $this->subCatCreditedCapacity->attributes()->createMany([
            [
                'name' => 'Report',
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
        $this->subCatCapacityObligation->attributes()->whereIn('name', ['Report'])->delete();
        $this->subCatCreditedCapacity->attributes()->whereIn('name', ['Report'])->delete();
    }
}
