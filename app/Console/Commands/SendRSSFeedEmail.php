<?php

namespace App\Console\Commands;

use App\Mail\SendRssFeedSubscriberEmail;
use App\Models\Subscriber;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendRSSFeedEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update-subscribers:rss-feed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /**
         * This command is not in use for now.
         */

        $pages = $this->getRecords('Page');
        $posts = $this->getRecords('Post');
        $medias = $this->getRecords('MediaLibrary');

        $rssFeedSubscribers = $this->getSubscribers();

        $todaysPublishedRecords = collect([]);

        $todaysPublishedRecords = $todaysPublishedRecords->merge($pages)->merge($posts)->merge($medias);

        foreach ($rssFeedSubscribers as $subscriber) 
        {
            Mail::to($subscriber->email)->send(new SendRssFeedSubscriberEmail($todaysPublishedRecords));
        }
    }
    
    /**
     * getRecords
     *
     * @param  mixed $model
     * @return mixed
     */
    private function getRecords($model)
    {
        $model = 'App\\Models\\' . $model;

        $records = $model::todaysPublishedRecords()->get();

        return $records;
    }
    
    /**
     * getSubscribers
     *
     * @return mixed
     */
    private function getSubscribers()
    {
        return Subscriber::rss()->select('id', 'email')->get();
    }
}
