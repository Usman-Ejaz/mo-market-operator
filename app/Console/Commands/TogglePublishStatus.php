<?php

namespace App\Console\Commands;

use App\Models\Training;
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
    protected $description = 'Publish and Unpulished the posts, pages and jobs according to their scheduled time.';

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

                $startDateInCurrentDateRange = $currentDate->gte(parseDate($object->start_datetime));
                $currentDateInEndDateRange = $object->end_datetime ? $currentDate->lte(parseDate($object->end_datetime)) : true;
                $isPublished = $object->isPublished();

                // For publishing the contents
                if ($startDateInCurrentDateRange && $currentDateInEndDateRange && !$isPublished) {
                    $object->update(['published_at' => now()]);
                }

                // For unpublishing the contents
                if ($object->end_datetime && $currentDate->gte(parseDate($object->end_datetime)) && $isPublished) {
                    $object->update(['published_at' => null]);
                }

            } catch (\Throwable $th) {

            }
        }

        // Handle publishing stuff for trainings module
        // As trainings module have different scenario

        $trainings = Training::scheduledRecords()->select('id', 'start_datetime', 'end_datetime', 'status')->get();

        if ($trainings->count() > 0) {
            foreach ($trainings as $training)
            {
                try {

                    $startDateInCurrentDateRange = $currentDate->gte(parseDate($training->start_datetime));
                    $currentDateInEndDateRange = $training->end_datetime ? $currentDate->lte(parseDate($training->end_datetime)) : true;
                    $isOpen = $training->status == '1';

                    // For publishing the contents
                    if ($startDateInCurrentDateRange && $currentDateInEndDateRange && !$isOpen) {
                        $training->update(['status' => '1']);
                    }

                    // For unpublishing the contents
                    if ($training->end_datetime && $currentDate->gte(parseDate($training->end_datetime)) && $isOpen) {
                        $training->update(['status' => '0']);
                    }

                } catch (\Throwable $th) {
                    //throw $th;
                }
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
