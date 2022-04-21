<?php

namespace App\Listeners;

use App\Mail\SendChatHistoryEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailToGeneralReceivers implements ShouldQueue
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
        $emails = settings('notification_emails');
        if ($emails && !empty($emails)) {
            $emails = explode(",", $emails);

            foreach($emails as $email) {
                Mail::to($email)->send(new SendChatHistoryEmail($event->history, $event->initiator));
            }
        }
    }
}
