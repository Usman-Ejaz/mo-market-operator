<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class EmailConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $fromEmails = settings('from_emails');

        if ($fromEmails) {
            config(['mail.from.address' => $fromEmails]);
            config(['mail.from.name' => config('app.name')]);
        }
    }
}
