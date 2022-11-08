<?php

use App\Models\MOData;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SeedMODataMigration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        MOData::create([
            'title' => "Marginal Cost vs Demand",
            'description' => "",
            'external_graph_id' => 1,
        ]);

        MOData::create([
            'title' => 'Fuel Mix',
            'description' => "",
            'external_graph_id' => 2,
        ]);

        MOData::create([
            'title' => 'Hourly Generation Profile',
            'description' => "",
            'external_graph_id' => 3,
        ]);

        MOData::create([
            'title' => "DISCO's Consumption",
            'description' => "",
            'external_graph_id' => 4,
        ]);

        // MOData::create([
        //     'title' => "Future Projections",
        //     'description' => ""
        // ]);

        // /** @var MOData $marketInfo */
        // $marketInfo = MOData::create([
        //     'title' => "Market Information",
        //     'description' => "",
        // ]);

        // $marketInfo->extraAttributes()->createMany([

        //     ['title' => 'Number of market participants'],
        //     ['title' => 'Number of service providers'],
        //     ['title' => 'Total Imbalances'],
        //     ['title' => 'Settled'],
        //     ['title' => 'Total amount settled'],
        //     ['title' => 'Total contract registered'],
        //     ['title' => 'Total capacity certificates given'],
        // ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        MOData::query()->truncate();
    }
}
