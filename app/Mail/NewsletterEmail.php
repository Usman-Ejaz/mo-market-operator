<?php

namespace App\Mail;

use App\Models\Newsletter;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $newsletter;
    public $signedURL;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Newsletter $newsletter, $signedURL)
    {
        $this->newsletter = $newsletter;
        $this->signedURL = $signedURL;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.newsletter', [
            'description' => $this->newsletter->description,
            'url' => $this->signedURL
        ])
        ->subject($this->newsletter->subject);
    }
}
