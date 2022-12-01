<?php

namespace Database\Seeders;

use App\Models\MOData;
use Illuminate\Database\Seeder;

class MODataTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        MOData::create([
            'title' => "Marginal Cost vs Demand",
            'description' => ""
        ]);

        MOData::create([
            'title' => 'Fuel Mix',
            'description' => ""
        ]);

        MOData::create([
            'title' => 'Hourly Generation Profile',
            'description' => ""
        ]);

        MOData::create([
            'title' => "DISCO's Consumption",
            'description' => ""
        ]);

        MOData::create([
            'title' => "Future Projections",
            'description' => ""
        ]);

        /** @var MOData $marketInfo */
        $marketInfo = MOData::create([
            'title' => "Market Information",
            'description' => "",
        ]);

        $marketInfo->extraAttributes()->createMany([

            ['title' => 'Number of market participants'],
            ['title' => 'Number of service providers'],
            ['title' => 'Total Imbalances'],
            ['title' => 'Settled'],
            ['title' => 'Total amount settled'],
            ['title' => 'Total contract registered'],
            ['title' => 'Total capacity certificates given'],
        ]);
    }
}
