<?php

use App\Models\ReportAttributeType;
use App\Models\ReportCategory;
use App\Models\ReportSubCategory;
use Illuminate\Database\Migrations\Migration;

class SeedFirmCapacityCertificateModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {


        /** @var ReportCategory $category */
        $category = ReportCategory::create(['name' => 'Firm Capacity Certificate']);

        foreach ($this->subCategoryData() as $subCategory) {
            $subCategory = collect($subCategory);
            /** @var ReportSubCategory $subCategoryDB */
            $subCategoryDB = $category->subCategories()->create(['name' => $subCategory->get('name')]);
            $subCategoryDB->attributes()->createMany($subCategory->get('attributes'));
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
                'name' => "All FCC (Generator)",
                'attributes' => [
                    [
                        'name' => 'Certificate ID (Unit Range)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Identification Code of Generation Unit',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Associated Capacity (MW)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Certificate Issue Date',
                        'report_attribute_type_id' =>  $attributeTypes['date']->id,
                    ],

                    [
                        'name' => 'Certificate Expiry Date',
                        'report_attribute_type_id' =>  $attributeTypes['date']->id,
                    ],

                    [
                        'name' => 'Status',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],
                ],
            ],

            [
                'name' => "Contracted FCC (Generator)",
                'attributes' => [
                    [
                        'name' => 'Contracted Buyer Name',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Contracted Certificate (Unit Range)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Identification Code of Generation Unit',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Name of Generation Unit',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Associated Capacity (MW)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Certificate Issue Date',
                        'report_attribute_type_id' =>  $attributeTypes['date']->id,
                    ],

                    [
                        'name' => 'Certificate Expiry Date',
                        'report_attribute_type_id' =>  $attributeTypes['date']->id,
                    ],

                    [
                        'name' => 'Status',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],
                ],
            ],

            [
                'name' => "FC (Demand/Supplier)",
                'attributes' => [
                    [
                        'name' => 'Identification Code of Generation Unit',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Name of Generation Unit',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => 'Contracted Certificate (Unit Range)',
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => "Unit Number",
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => "Contracted Capacity (MW)",
                        'report_attribute_type_id' =>  $attributeTypes['string']->id,
                    ],

                    [
                        'name' => "Contract Effective Date",
                        'report_attribute_type_id' =>  $attributeTypes['date']->id,
                    ],

                    [
                        'name' => "Contract Expiry Date",
                        'report_attribute_type_id' =>  $attributeTypes['date']->id,
                    ],

                    [
                        'name' => "Status",
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
        ReportCategory::where('name', 'Firm Capacity Certificate')->delete();
    }
}
