<?php

use Illuminate\Database\Migrations\Migration;
use App\Models\ReportAttributeType;
use App\Models\ReportCategory;
use App\Models\ReportSubCategory;

class SeedSecurityCoverReportModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var ReportCategory $category */
        $category = ReportCategory::create(['name' => 'Security Cover']);

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
                'name' => "Current Balance",
                'attributes' => [
                    [
                        'name' => 'Security Cover Balance',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Make a Request',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],
                ],
            ],

            [
                'name' => "SGF Balance",
                'attributes' => [
                    [
                        'name' => 'SGF Balance',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Make a Request',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],
                ],
            ],

            [
                'name' => "Security Cover History",
                'attributes' => [
                    [
                        'name' => 'Received',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Utilized',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Couped',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],
                ],
            ],

            [
                'name' => "Security Guarantee Fund History",
                'attributes' => [
                    [
                        'name' => 'Received',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Utilized',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Couped',
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
        ReportCategory::where('name', 'Security Cover')->delete();
    }
}
