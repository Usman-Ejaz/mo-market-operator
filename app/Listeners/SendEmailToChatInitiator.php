<?php

namespace App\Listeners;

use App\Mail\SendChatHistoryEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendEmailToChatInitiator implements ShouldQueue
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
        $initiator = $event->initiator;
        $history = $event->history;

        $isAllowedToSendChatEmail = $initiator['send_chat_history'] === 1;

        if ($isAllowedToSendChatEmail) {
            Mail::to($initiator['email'])->send(new SendChatHistoryEmail($history, $initiator));
        }
    }
}
