<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatbotChatHistoryEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $history;
    public $initiator;

    /**
     * Create a new event instance.
     *
     * @param  mixed $history
     * @param  mixed $initiator
     * @return void
     */
    public function __construct($history, $initiator)
    {
        $this->history = $history;
        $this->initiator = $initiator;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
