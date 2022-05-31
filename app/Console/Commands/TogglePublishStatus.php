<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class TogglePublishStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toggle:publish-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $posts = $this->getRecords('Post');
        $pages = $this->getRecords('Page');
        $jobs = $this->getRecords('Job');
        
        $records = collect([]);

        // Merging all data into one array
        $records = $records->merge($posts)->merge($pages)->merge($jobs);

        $currentDate = (now())->second(0);
        
        foreach ($records as $object) 
        {
            try {

                // For publishing the contents
                if ($currentDate->gte(parseDate($object->start_datetime)) && $currentDate->lte(parseDate($object->end_datetime))) {
                    if ($object->published_at === null) {
                        $object->update(['published_at' => now()]);
                    }
                }

                // For unpublishing the contents
                if ($currentDate->gte(parseDate($object->end_datetime))) {
                    if ($object->published_at !== null) {
                        $object->update(['published_at' => null]);
                    }
                }

            } catch (\Throwable $th) {
                
            }
        }
    }
    
    /**
     * getRecords
     *
     * @param  string $model
     * @return mixed
     */
    private function getRecords($model)
    {
        $model = 'App\\Models\\' . $model;

        $records = $model::scheduledRecords()->select('id', 'published_at', 'start_datetime', 'end_datetime')->get();

        return $records;
    }
}
