<?php

use App\Models\ReportAttributeType;
use App\Models\ReportCategory;
use Illuminate\Database\Migrations\Migration;

class SeedMeteringDataReportModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var ReportCategory $category */
        $category = ReportCategory::create(['name' => 'Metering Data']);

        foreach ($this->subCategoryData() as $subCategory) {
            $subCategory = $subCategory;
            /** @var ReportSubCategory $subCategoryDB */
            $subCategoryDB = $category->subCategories()->create(['name' => $subCategory['name']]);
            $subCategoryDB->attributes()->createMany($subCategory['attributes']);
        }
    }

    private function subCategoryData()
    {
        $attributes = ReportAttributeType::all();
        $attributeTypes = [];
        foreach ($attributes as $att) {
            $attributeTypes[$att->name] = $att;
        }
        return [
            [
                'name' => "CDPs List/Details",
                'attributes' => [
                    [
                        'name' => 'CDP ID',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'CDP Name',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Station',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'From Customer',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'To Customer',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => "Effective From",
                        'report_attribute_type_id' =>  $attributeTypes['date']->id,
                    ],

                    [
                        'name' => "Effective To",
                        'report_attribute_type_id' =>  $attributeTypes['date']->id,
                    ],

                    [
                        'name' => "Line Voltage",
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Meter ID',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => "Connected From",
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => "Connected To",
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],
                ],
            ],

            [
                'name' => "Hourly Data",
                'attributes' => [
                    [
                        'name' => 'DateTime Stamp',
                        'report_attribute_type_id' =>  $attributeTypes['date']->id,
                    ],

                    [
                        'name' => 'CDP Description',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'CDP ID',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'CDP Name',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Station',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'From Customer',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'To Customer',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Incremental Active Energy Export (MWh)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Incremental Active Energy Import (MWh)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Incremental Reactive Energy Import (MWarh)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Incremental Reactive Energy Export (MWarh)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],
                ],
            ],
        ];
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        ReportCategory::where('name', 'Metering Data')->delete();
    }
}
