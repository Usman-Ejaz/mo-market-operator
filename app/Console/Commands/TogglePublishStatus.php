<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class TogglePublishStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'toggle:scheduled-records';

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
            if ($object->published_at === null) {   // if not published
                if ($currentDate->gte($object->start_datetime)) {
                    $object->published_at = now();
                    $object->save();
                }
            } else {
                
            }
        }
    }

    private function getRecords($model)
    {
        $model = 'App\\Models\\' . $model;

        $records = $model::scheduledRecords()->get();

        return $records;
    }
}
