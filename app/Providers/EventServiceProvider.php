<?php

namespace App\Providers;

use App\Events\ChatbotChatHistoryEvent;
use App\Events\NewContactQueryHasArrived;
use App\Events\QueryReplyEvent;
use App\Events\SiteSearchEvent;
use App\Listeners\LogSearchKeyword;
use App\Listeners\SendEmailToChatInitiator;
use App\Listeners\SendEmailToGeneralReceivers;
use App\Listeners\SendEmailToQueryReceivers;
use App\Listeners\SendNotificationToNotifiableUsers;
use App\Listeners\SendQueryReplyEmail;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        SiteSearchEvent::class => [
            LogSearchKeyword::class
        ],
        NewContactQueryHasArrived::class => [
            SendNotificationToNotifiableUsers::class,
            SendEmailToQueryReceivers::class
        ],
        ChatbotChatHistoryEvent::class => [
            SendEmailToChatInitiator::class,
            SendEmailToGeneralReceivers::class
        ],
        QueryReplyEvent::class => [
            SendQueryReplyEmail::class,
        ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
