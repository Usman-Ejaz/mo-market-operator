<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendChatHistoryEmail extends Mailable
{
    use Queueable, SerializesModels;

    protected $history = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($history)
    {
        $this->history = $history;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.send-chat-history-email', [
            'history' => $this->history
        ])
        ->subject("Chatbot chat history for ". today()->format('Y-m-d') .".");
    }
}
