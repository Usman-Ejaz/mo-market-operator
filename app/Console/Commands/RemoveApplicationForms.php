<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class RemoveApplicationForms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:downloaded-forms';

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
        $files = $this->getForms();

        foreach ($files as $file) {
            $filename = basename($file);

            $path = str_replace($filename, "", $file);

            removeFile($path, $filename);
        }
    }

    private function getForms()
    {
        return Storage::disk(config('settings.storage_disk'))->allFiles('clients/forms');
    }
}
