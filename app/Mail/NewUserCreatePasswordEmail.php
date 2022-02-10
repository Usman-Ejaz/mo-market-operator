<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class NewUserCreatePasswordEmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $signedURL = URL::temporarySignedRoute('create-password', 
            now()->addMinutes(config("settings.createPassowrdLinkExpiryTime")), ['user' => $this->user->email]);

        return $this->markdown('mail.new-user-create-password', ['url' => $signedURL])
        ->subject("Welcome, Please create your password");
    }
}
