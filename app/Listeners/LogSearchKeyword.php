<?php

namespace App\Listeners;

use App\Events\SiteSearchEvent;
use App\Models\SearchStatistic;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class LogSearchKeyword
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
     * @param  object  $event
     * @return void
     */
    public function handle(SiteSearchEvent $event)
    {
        $keyword = $event->searchKeyword;

        $stats = SearchStatistic::create(['keyword' => $keyword]);
        $stats->increment('count');
    }
}
