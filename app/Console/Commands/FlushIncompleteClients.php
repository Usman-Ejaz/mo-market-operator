<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\ClientAttachment;
use App\Models\ClientDetail;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FlushIncompleteClients extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'flush:incomplete-clients';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove all the clients from resource after 15 days who have started the registration process but didn\'t complete.';

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
        $clients = $this->getIncompleteClients();

        if ($clients->count() > 0)
        {
            foreach ($clients as $client)
            {
                $today = today();
                $clientCreated = Carbon::parse(parseDate($client->created_at))->addDays(config('settings.client_removal_threshold'));
                
                if ($today->gt($clientCreated)) {
                    $this->removeClient($client);
                }
            }
        }
    }
    
    /**
     * getIncompleteClients
     *
     * @return mixed
     */
    private function getIncompleteClients()
    {
        return Client::incomplete()->get();
    }
    
    /**
     * removeClient
     *
     * @param  mixed $client
     * @return void
     */
    private function removeClient($client)
    {
        foreach ($client->details as $record) {
            removeFile(ClientDetail::SIGNATURE_DIR, $record->signature);
            $record->delete();
        }

        foreach ($client->attachments as $record) {
            removeFile(ClientAttachment::DIR, $record->file);
            $record->delete();
        }

        $client->delete();
    }
}
