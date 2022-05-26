<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendRssFeedSubscriberEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $todaysRecords = null;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($todaysRecords)
    {
        $this->todaysRecords = $todaysRecords;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.send-rss-feed-subscriber-email', [
            'data' => $this->todaysRecords
        ])
        ->subject('Important Update - RSS Feed for Today');
    }
}
