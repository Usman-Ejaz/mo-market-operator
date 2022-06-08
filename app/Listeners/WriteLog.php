<?php

namespace App\Listeners;

use App\Events\ActivityLogEvent;
use App\Models\ActivityLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class WriteLog implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ActivityLogEvent  $event
     * @return void
     */
    public function handle(ActivityLogEvent $event)
    {
        $module = str_replace("App\\Models\\", "", get_class($event->model));
        
        $message = $module . ' ' . $event->message;

        ActivityLog::create([
            'message' => $message,
            'type' => $event->type,
            'model' => get_class($event->model),
            'module' => $module,
            'done_by' => $event->userId,
            'new' => $event->model->toJson(),
            'old' => json_encode($event->model->getChanges())
        ]);
    }
}
