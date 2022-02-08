<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;

class UserCreated extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $user = null;

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
        $url = URL::temporarySignedRoute(
            'create-password', now()->addMinutes(config("setting.createPassowrdLinkExpiryTime")), ['user' => $this->user->email]
        );
        return $this->markdown('mail.user-created', ['url' => $url]);
    }
}
