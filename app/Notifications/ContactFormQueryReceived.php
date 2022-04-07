<?php

namespace App\Notifications;

use App\Models\ContactPageQuery;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormQueryReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected $contactPageQuery = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ContactPageQuery $contactPageQuery)
    {        
        $this->contactPageQuery = $contactPageQuery;
        $this->afterCommit();
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'link' => route("admin.contact-page-queries.show", $this->contactPageQuery->id),
            'title' => $this->contactPageQuery->subject,
            'time' => $this->contactPageQuery->created_at->diffForHumans()
        ];
    }
}
