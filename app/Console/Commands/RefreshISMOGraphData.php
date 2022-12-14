<?php

namespace App\Console\Commands;

use App\Jobs\RefreshISMOGraphData as JobsRefreshISMOGraphData;
use Exception;
use Illuminate\Console\Command;

class RefreshISMOGraphData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ismo-graph:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh graph data from ISMO external API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try {
            JobsRefreshISMOGraphData::dispatchSync();
        } catch (Exception $e) {
            return 1;
        }
        $this->info("Data refreshed from IMSO graph API");
        return 0;
    }
}
