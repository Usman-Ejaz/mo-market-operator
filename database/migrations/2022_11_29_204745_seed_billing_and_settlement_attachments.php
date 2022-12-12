<?php

use App\Models\ReportAttributeType;
use App\Models\ReportCategory;
use App\Models\ReportSubCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedBillingAndSettlementAttachments extends Migration
{
    private $catBillingAndSettlement;
    private $subCatMonthlyPSS;
    private $subCatMonthlyFSS;
    private $subCatMonthlyESS;
    private $subCatAnnually;
    private $subCatArchive;

    public function __construct()
    {
        $this->catBillingAndSettlement = ReportCategory::firstWhere('name', "Billing and Settlement");
        $this->subCatMonthlyPSS = $this->catBillingAndSettlement->subCategories()->firstWhere('name', 'Monthly PSS');
        $this->subCatMonthlyFSS = $this->catBillingAndSettlement->subCategories()->firstWhere('name', 'Monthly FSS');
        $this->subCatMonthlyESS = $this->catBillingAndSettlement->subCategories()->firstWhere('name', 'Monthly ESS');
        $this->subCatAnnually = $this->catBillingAndSettlement->subCategories()->firstWhere('name', 'Annually');
        $this->subCatArchive = $this->catBillingAndSettlement->subCategories()->firstWhere('name', 'Archive');
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


        $this->subCatMonthlyPSS->attributes()->createMany([
            [
                'name' => 'PSS Summary Report',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ],
            [
                'name' => 'PSS Complete Report',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ]
        ]);

        $this->subCatMonthlyFSS->attributes()->createMany([
            [
                'name' => 'FSS Summary Report',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ],
            [
                'name' => 'FSS Complete Report',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ]
        ]);

        $this->subCatMonthlyESS->attributes()->createMany([
            [
                'name' => 'ESS Summary Report',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ],
            [
                'name' => 'ESS Complete Report',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ]
        ]);

        $this->subCatAnnually->attributes()->createMany([
            [
                'name' => 'BMC Summary Report',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ],
            [
                'name' => 'Complete Report',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ]
        ]);

        $this->subCatArchive->attributes()->createMany([
            [
                'name' => 'Summary Report',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ],
            [
                'name' => 'Full Report',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->subCatMonthlyPSS->attributes()->whereIn('name', ['PSS Summary Report', 'PSS Complete Report'])->delete();
        $this->subCatMonthlyFSS->attributes()->whereIn('name', ['FSS Summary Report', 'FSS Complete Report'])->delete();
        $this->subCatMonthlyESS->attributes()->whereIn('name', ['ESS Summary Report', 'ESS Complete Report'])->delete();
        $this->subCatAnnually->attributes()->whereIn('name', ['BMC Summary Report', 'Complete Report'])->delete();
        $this->subCatArchive->attributes()->whereIn('name', ['Summary Report', 'Full Report'])->delete();
    }
}
