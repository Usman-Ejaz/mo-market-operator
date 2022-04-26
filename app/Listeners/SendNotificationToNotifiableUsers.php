<?php

namespace App\Listeners;

use App\Notifications\ContactFormQueryReceived;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotificationToNotifiableUsers implements ShouldQueue
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
        $users = getNotifiableUsers();
        
        if ($users->count() > 0) {
            foreach ($users as $user) {
                $user->notify(new ContactFormQueryReceived($event->contactPageQuery));
            }
        }
    }
}
