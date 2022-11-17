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
            'title' => "Market Data Glance",
            'description' => '<p><iframe align="middle" frameborder="0" height="800" name="MO Website" scrolling="no" src="https://app.powerbi.com/view?r=eyJrIjoiZjBjOTM4MjYtMGVlMS00MTA3LTk5YzktMDZhZWQ3NDQyOTdkIiwidCI6Ijc2Mzk0NTVkLWIwMzYtNDJlYS05ZDE0LWVhNzYzYTJlOWFmYiIsImMiOjl9&amp;pageName=ReportSection" width="100%"></iframe></p>

                <h2>Overview</h2>
                
                <p>Write a breif overview</p>
                
                <h2>Data Source</h2>
                
                <p>Write some details about the data source</p>
                
                <p>&lt;%files_slot%/&gt;</p>
                
                <h2>Disclaimer</h2>
                
                <p>Write a brief disclaimer</p>',
            'external_graph_id' => null,
        ]);

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
