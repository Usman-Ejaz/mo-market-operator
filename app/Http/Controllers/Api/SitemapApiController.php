<?php

namespace App\Http\Controllers\Api;

use App\Models\Menu;
use App\Models\Page;
use Illuminate\Http\Request;

class SitemapApiController extends BaseApiController
{
    
    /** 
     * 
     * @OA\Tag(
     *     name="Sitemap",
     *     description="API Endpoints of Sitemap"
     * )
     * 
     * @OA\Get(
     *      path="/sitemap",
     *      operationId="index",
     *      tags={"Sitemap"},
     *      summary="Get list of Sitemap Links",
     *      description="Returns list of Sitemap Links",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Success"          
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Could not found",
     *      ),
     *  )
     */
    public function index()
    {
        try {
            $currentTheme = settings('current_theme');

            $menus = Menu::byTheme($currentTheme)->select('name', 'submenu_json')->get();

            foreach ($menus as $menu) {
                $menu->children = json_decode($menu->submenu_json);
                unset($menu->submenu_json);
            }

            if ($menus) {
                return $this->sendResponse($menus, __("messages.success"));
            } else {
                return $this->sendResponse([], __("messages.data_not_found"));
            }
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
}
