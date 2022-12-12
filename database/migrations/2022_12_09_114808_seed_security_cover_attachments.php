<?php

use App\Models\ReportAttributeType;
use App\Models\ReportCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedSecurityCoverAttachments extends Migration
{
    private $catSecurityCover;
    private $subCatCurrentBalance;
    private $subCatSGFBalance;

    public function __construct()
    {
        $this->catSecurityCover = ReportCategory::firstWhere('name', 'Security Cover');
        $this->subCatCurrentBalance = $this->catSecurityCover->subCategories()->firstWhere('name', "Current Balance");
        $this->subCatSGFBalance = $this->catSecurityCover->subCategories()->firstWhere('name', "SGF Balance");
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

        $this->subCatCurrentBalance->attributes()->createMany([
            [
                'name' => 'Integration with ERP',
                'report_attribute_type_id' =>  $attributeTypes['file']->id,
            ],
        ]);

        $this->subCatSGFBalance->attributes()->createMany([
            [
                'name' => 'Integration with ERP',
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
        $this->subCatCurrentBalance->attributes()->whereIn('name', ['Integration with ERP'])->delete();
        $this->subCatSGFBalance->attributes()->whereIn('name', ['Integration with ERP'])->delete();
    }
}
