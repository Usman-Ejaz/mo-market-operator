<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HandleContactFormQueryMail extends Mailable
{
    use Queueable, SerializesModels;

    private $contactPageQuery = null;

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
        return $this->markdown('mail.contact-form-query', [
            'contactPageQuery' => $this->contactPageQuery
        ])->subject(__('New contact form query submitted'));
    }
}
