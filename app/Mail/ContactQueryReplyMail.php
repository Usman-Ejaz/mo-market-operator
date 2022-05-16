<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ContactQueryReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($contactPageQuery)
    {
        $this->contactPageQuery = $contactPageQuery;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.query-reply', ['contactPageQuery' => $this->contactPageQuery])
            ->subject($this->contactPageQuery->subject . ' - Reply');
    }
}
