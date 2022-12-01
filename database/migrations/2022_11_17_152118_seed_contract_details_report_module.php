<?php

use App\Models\Report;
use App\Models\ReportAttributeType;
use App\Models\ReportCategory;
use App\Models\ReportSubCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedContractDetailsReportModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var ReportCategory $category */
        $category = ReportCategory::create(['name' => 'Contract Details']);

        /** @var ReportSubCategory $subCategory */
        $subCategory = $category->subCategories()->create(['name' => "Contract Details"]);

        $attributes = ReportAttributeType::all();
        $attributeTypes = [];
        foreach ($attributes as $att) {
            $attributeTypes[$att->name] = $att;
        }
        $subCategory->attributes()->createMany([
            [
                'name' => 'Type',
                'report_attribute_type_id' => $attributeTypes['string']->id,
            ],
            [
                'name' => 'Buyer Name',
                'report_attribute_type_id' => $attributeTypes['string']->id,
            ],
            [
                'name' => 'Buyer Category',
                'report_attribute_type_id' => $attributeTypes['string']->id,
            ],
            [
                'name' => 'Seller Name',
                'report_attribute_type_id' => $attributeTypes['string']->id,
            ],
            [
                'name' => 'Seller Category',
                'report_attribute_type_id' => $attributeTypes['string']->id,
            ],
            [
                'name' => 'Product',
                'report_attribute_type_id' => $attributeTypes['string']->id,
            ],
            [
                'name' => 'Quantity (MW)',
                'report_attribute_type_id' => $attributeTypes['string']->id,
            ],
            [
                'name' => 'Effective Date',
                'report_attribute_type_id' => $attributeTypes['date']->id,
            ],
            [
                'name' => 'Expirt Date',
                'report_attribute_type_id' => $attributeTypes['date']->id,
            ],
            [
                'name' => 'Status',
                'report_attribute_type_id' => $attributeTypes['string']->id,
            ],
            [
                'name' => 'Remarks',
                'report_attribute_type_id' => $attributeTypes['string']->id,
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
        ReportCategory::where('name', 'Contract Details')->first()->delete();
    }
}
