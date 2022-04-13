<?php

namespace App\Notifications;

use App\Models\ContactPageQuery;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactFormQueryReceived extends Notification implements ShouldQueue
{
    use Queueable;

    private $contactPageQuery = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ContactPageQuery $contactPageQuery)
    {        
        $this->contactPageQuery = $contactPageQuery;
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
        // $notification_receiver_emails = explode(",", settings('notification_emails'));
        
        // if ($notification_receiver_emails === null || empty($notification_receiver_emails)) {
        //     $notification_receiver_emails = config('settings.fallback_email');
        // }

        // return (new ContactFormQueryMail($this->contactPageQuery))
        //     ->from($this->contactPageQuery->email)
        //     ->to($notification_receiver_emails);
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
            'time' => Carbon::create(parseDate($this->contactPageQuery->created_at))->diffForHumans()
        ];
    }
}
