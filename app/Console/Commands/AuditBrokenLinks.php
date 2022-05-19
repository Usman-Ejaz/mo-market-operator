<?php

namespace App\Console\Commands;

use App\Models\Menu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

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

    private $brokenLinks = null;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->brokenLinks = [];
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
        
        $this->auditLinks($menus);
        
        $brokenLinks = $this->brokenLinks;

        if (count($brokenLinks) > 0) {
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
        return Menu::byTheme()->active()->select('id', 'submenu_json')->latest()->get();
    }
    
    /**
     * auditLinks
     *
     * @param  mixed $menus
     * @return array
     */
    private function auditLinks($menus)
    {        
        foreach ($menus as $menu) 
        {
            $submenuJson = json_decode($menu->submenu_json, true);

            $this->searchMenu($submenuJson, $menu->id);
        }
    }
    
    /**
     * searchMenu
     *
     * @param  mixed $submenu
     * @param  mixed $menuId
     * @return void
     */
    private function searchMenu($submenu, $menuId)
    {
        foreach ($submenu as $menu) 
        {
            if (isset($menu['children']) && is_array($menu['children'])) 
            {
                $this->searchMenu($menu['children'], $menuId);
            }

            $link = config('settings.client_app_base_url');
            
            if (isset($menu['anchor'])) {

                if (strpos($menu['anchor'], "https:") !== false) {
                    $link = $menu['anchor'];
                } else if ($menu['anchor'] === "#") {
                    continue;
                } else {
                    $link .= $menu['anchor'];
                }

            } else if (isset($menu['slug'])) {
                $link .= $menu['slug'];
            }

            $this->checkURL($link, $menuId);
        }
    }
    
        
    /**
     * checkURL
     *
     * @param  mixed $link
     * @param  mixed $menuId
     * @return void
     */
    private function checkURL($link, $menuId)
    {
        $response = Http::get($link);
        
        if (! $response->ok()) {
            array_push($this->brokenLinks, ['link' => $link, 'menu_id' => $menuId]);
        }
    }
}
