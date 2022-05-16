<?php

namespace App\Listeners;

use App\Mail\ContactQueryReplyMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendQueryReplyEmail implements ShouldQueue
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
    public function handle($event)
    {
        Mail::to($event->contactPageQuery->email)
            ->send(new ContactQueryReplyMail($event->contactPageQuery));
    }
}
