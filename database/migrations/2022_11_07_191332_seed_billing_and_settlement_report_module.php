<?php

use App\Models\ReportAttributeType;
use App\Models\ReportCategory;
use App\Models\ReportSubCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedBillingAndSettlementReportModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /** @var ReportCategory $category */
        $category = ReportCategory::create($this->categoryData());

        $subcategories = [];
        foreach ($this->subCategoryData() as $value) {
            $subcategories[$value['name']] = $category->subCategories()->create($value);
        }

        $attributeTypes = [];
        foreach ($this->attributesTypeData() as $value) {
            $attributeTypes[$value['name']] = ReportAttributeType::create($value);
        }

        $subcategories['Monthly PSS']->attributes()->createMany([
            [
                'name' => 'Settlement Year',
                'report_attribute_type_id' => $attributeTypes['year']->id,
            ],
            [
                'name' => 'Settlement Month',
                'report_attribute_type_id' => $attributeTypes['month']->id,
            ]
        ]);

        $subcategories['Monthly FSS']->attributes()->createMany([
            [
                'name' => 'Settlement Year',
                'report_attribute_type_id' => $attributeTypes['year']->id,
            ],
            [
                'name' => 'Settlement Month',
                'report_attribute_type_id' => $attributeTypes['month']->id,
            ]
        ]);

        $subcategories['Monthly ESS']->attributes()->createMany([
            [
                'name' => 'Settlement Year',
                'report_attribute_type_id' => $attributeTypes['year']->id,
            ],
            [
                'name' => 'Settlement Month',
                'report_attribute_type_id' => $attributeTypes['month']->id,
            ]
        ]);

        $subcategories['Annually']->attributes()->createMany([
            [
                'name' => 'Settlement Year',
                'report_attribute_type_id' => $attributeTypes['year']->id,
            ]
        ]);

        $subcategories['Archive']->attributes()->createMany([
            [
                'name' => 'Settlement Year',
                'report_attribute_type_id' => $attributeTypes['year']->id,
            ],
            [
                'name' => 'Settlement Month',
                'report_attribute_type_id' => $attributeTypes['month']->id,
            ]
        ]);
    }

    public function categoryData()
    {
        return ['name' => 'Billing and Settlement'];
    }


    private function subCategoryData()
    {
        return collect([
            ['name' => 'Monthly PSS'],
            ['name' => 'Monthly FSS'],
            ['name' => 'Monthly ESS'],
            ['name' => 'Annually'],
            ['name' => 'Archive'],
        ]);
    }

    private function attributesTypeData()
    {
        return collect([
            ['name' => "date"],
            ['name' => "month"],
            ['name' => "year"],
            ['name' => "string"],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $categroyName = $this->categoryData()['name'];

        $category = ReportCategory::where('name', $categroyName)->first();
        $category->delete();

        $attributeTypeNames = $this->attributesTypeData()->map(function ($data) {
            return $data['name'];
        });

        ReportAttributeType::whereIn('name', $attributeTypeNames)->delete();
    }
}
