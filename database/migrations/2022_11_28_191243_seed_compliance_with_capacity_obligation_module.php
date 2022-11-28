<?php

use App\Models\ReportAttributeType;
use App\Models\ReportCategory;
use App\Models\ReportSubCategory;
use Illuminate\Database\Migrations\Migration;

class SeedComplianceWithCapacityObligationModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var ReportCategory $category */
        $category = ReportCategory::create(['name' => 'Compliance With Capacity Obligation']);

        foreach ($this->subCategoryData() as $subCategory) {
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
                'name' => "Capacity Obligation",
                'attributes' => [
                    [
                        'name' => 'BPC Name',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Category',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Annual Energy (MWh)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Absolute Maximum Demand (MW)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Max. Demand During System Peak Hours (MW)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Capacity Obligation (MW)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],
                ],
            ],

            [
                'name' => "Credited Capacity",
                'attributes' => [
                    [
                        'name' => 'Firm Capacity of Generation Planned Owned by MP',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Name',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Category',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Firm Capacity Sold by MP Through Registered Contracts',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Contracted Party Name (Capacity Sold)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Capacity Acquired by MP Through Registered Contracts',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Contracted Party Name (Capacity Acquired)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Capacity of Temporary Firm Capacity Certificates Issued by MO',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Declared Capacity to be Installed Multiplied by Equivalent Availability Factor',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Total Credited Capacity',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],
                ],
            ],

            [
                'name' => "Status",
                'attributes' => [
                    [
                        'name' => 'Percentage of Credited Capacity to Capacity Obligation',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Compliance Result',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],
                ],
            ]
        ];
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        ReportCategory::where('name', 'Compliance With Capacity Obligation')->delete();
    }
}
