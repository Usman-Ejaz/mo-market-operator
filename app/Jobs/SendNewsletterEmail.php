<?php

namespace App\Jobs;

use App\Mail\NewsletterEmail;
use App\Models\ActivityLog;
use App\Models\Newsletter;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class SendNewsletterEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $newsletter = null;
    public $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Newsletter $newsletter, $userId)
    {
        $this->newsletter = $newsletter;
        $this->userId = $userId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $subscribers = Subscriber::newletters()->select("id", "email")->get();

        foreach ($subscribers as $subscriber) {
            $signedURL = URL::signedRoute('unsubscribe', ['subscriber' => $subscriber->id]);
            Mail::to($subscriber->email)->send(new NewsletterEmail($this->newsletter, $signedURL));
        }

        ActivityLog::create([
            'message' => 'Newsletter was just sent.',
            'type' => 'sent',
            'model' => get_class($this->newsletter),
            'module' => 'Newsletter',
            'done_by' => $this->userId,
            'new' => $this->newsletter ? $this->newsletter->toJson() : null,
            'old' => $this->newsletter ? json_encode($this->newsletter->getChanges()) : null
        ]);
    }
}
