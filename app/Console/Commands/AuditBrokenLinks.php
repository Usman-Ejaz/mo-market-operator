<?php

namespace App\Console\Commands;

use App\Models\BrokenLink;
use App\Models\Menu;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
        $currentDateTime = now()->format('Y-m-d H:i:s');

        $this->writeLogs("--------------------- Start Working | {$currentDateTime} | ----------------------");

        $menus = $this->getMenusByActiveTheme();
        
        $this->writeLogs("Total Menus found: " . $menus->count());

        $this->auditLinks($menus);
        
        $brokenLinks = $this->brokenLinks;
        
        $this->writeLogs("Total Broken Links Found: " . count($brokenLinks));

        if (count($brokenLinks) > 0) {
            $this->removePrviousBrokenLinks();

            foreach ($brokenLinks as $link) {
                $this->addLinkToBrokenLinks($link);
            }
        }

        $this->writeLogs("------------------ End Working | {$currentDateTime} | ------------------");
    }
    
    /**
     * getMenusByActiveTheme
     *
     * @return object
     */
    private function getMenusByActiveTheme()
    {
        return Menu::byTheme()->active()->select('id', 'name', 'submenu_json')->latest()->get();
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
            
            unset($menu->submenu_json);
            
            $this->searchMenu($submenuJson, $menu);
        }
    }
    
    /**
     * searchMenu
     *
     * @param  mixed $submenu
     * @param  mixed $mainMenu
     * @return void
     */
    private function searchMenu($submenu, $mainMenu)
    {
        foreach ($submenu as $menu) 
        {
            if (isset($menu['children']) && is_array($menu['children'])) 
            {
                $this->searchMenu($menu['children'], $mainMenu);
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

            $this->checkURL($link, $menu, $mainMenu);
        }
    }
    
        
    /**
     * checkURL
     *
     * @param  mixed $link
     * @param  mixed $mainMenu
     * @return void
     */
    private function checkURL($link, $data, $mainMenu)
    {
        $response = Http::get($link);
        
        if (! $response->ok()) {
            array_push($this->brokenLinks, ['link' => $link, 'main_menu' => $mainMenu, 'menu' => $data]);
        }
    }
    
    /**
     * removePrviousBrokenLinks
     *
     * @return void
     */
    private function removePrviousBrokenLinks()
    {
        if (BrokenLink::all()->count() > 0) {
            $this->writeLogs("Removing old broken links from broken_links table");
            BrokenLink::truncate();
        }
    }

    private function addLinkToBrokenLinks($data)
    {        
        $this->writeLogs("Adding new broken link in broken_links table with data " . json_encode($data));

        BrokenLink::create([
            'link' => $data['link'],
            'title' => $data['menu']['title'],
            'menu_name' => $data['main_menu']['name'],
            'edit_link' => route('admin.menus.submenus', ['menu' => $data['main_menu']['id']])
        ]);
    }

    private function writeLogs($message, $type = 'info')
    {
        Log::channel('brokenLinksLog')->$type($message);
    }
}
