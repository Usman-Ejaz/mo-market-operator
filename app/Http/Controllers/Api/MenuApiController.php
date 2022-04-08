<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Settings;
use Exception;
use Illuminate\Http\Request;

class MenuApiController extends BaseApiController
{
    /**
     * 
     * @OA\Tag(
     *     name="Menus",
     *     description="API Endpoints of Menus"
     * )
     * 
     */ 

    /** 
     * @OA\Get(
     *      path="/menus",
     *      operationId="getMenus",
     *      tags={"Menus"},
     *      summary="Get list of Menus by active theme",
     *      description="Returns Menus by active theme",
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
    public function getMenus()
    {
        try {
            $currentTheme = settings('current_theme');

            $menus = Menu::byTheme($currentTheme)->active()->select('name', 'submenu_json', 'identifier')->get();

            $menus = $menus->map(function ($m) {
                return [
                    $m->identifier => [
                        'name' => $m->name,
                        'submenu_json' => json_decode($m->submenu_json)
                    ]
                ];
            });
            
            if ($menus->count() > 0) {
                return $this->sendResponse($menus, __("messages.success"));
            } else {
                return $this->sendResponse([], __("messages.data_not_found"));
            }
        } catch (Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
}
