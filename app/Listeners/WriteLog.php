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
        ActivityLog::create($event->data);
    }
}
