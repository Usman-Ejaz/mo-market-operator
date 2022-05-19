<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AuditBrokenLinks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'audit:broken-links';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Audit the broken links of the client site.';

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
         * 
         * 1. Get the menus w.r.t to active theme
         * 2. Audit menus by iterating each menu with links
         * 3. Check each link with client app base URL.
         * 4. If link sends 200 status code then skip the URL
         * 5. If link sends status code except 200 then add that link in broken links module.
         * 6. At each run of cron job, remove previous links from broken links module and add new ones.
         * 
         */        

        $menus = $this->getMenusByActiveTheme();

        $brokenLinks = $this->auditLinks($menus);

        if ($brokenLinks) {
            $this->removePrviousBrokenLinks();

            foreach ($brokenLinks as $link) {
                $this->addLinkToBrokenLinks($link);
            }
        }
    }
    
    /**
     * getMenusByActiveTheme
     *
     * @return void
     */
    private function getMenusByActiveTheme()
    {
        
    }
    
    /**
     * auditLinks
     *
     * @param  mixed $menus
     * @return array
     */
    private function auditLinks($menus)
    {
        return [];
    }
    
    /**
     * checkURL
     *
     * @param  mixed $link
     * @return void
     */
    private function checkURL($link)
    {
        
    }
}
