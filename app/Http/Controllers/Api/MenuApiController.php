<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DocumentCategory;
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

            $menuObject = [];

            foreach ($menus as $menu) {
                $menuObject[$menu->identifier] = [
                    'name' => $menu->name,
                    'submenu_json' => json_decode($menu->submenu_json)
                ];
            }

            if ($menus->count() > 0) {
                return $this->sendResponse($menuObject, __("messages.success"));
            } else {
                return $this->sendResponse([], __("messages.data_not_found"), HTTP_NOT_FOUND);
            }
        } catch (Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }


    /**
     * @OA\Get(
     *      path="/library-menus",
     *      operationId="libraryMenus",
     *      tags={"Menus"},
     *      summary="Get list of Library Menus",
     *      description="Get list of Library Menus",
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
    public function libraryMenus()
    {
        try {
            $docsCategories = DocumentCategory::parents()->with('children')->oldest()->select('id', 'slug', 'name')->get();

            $docsMenus = [];
            foreach ($docsCategories as $category) {
                $menu = [
                    'title' => $category->name,
                    'slug' => $category->slug,
                    'link_prefix' => '/library/' . $category->slug
                ];

                if ($category->children->count() > 0) {
                    $menu['children'] = $this->prepareSubMenu($category->children);
                }

                $docsMenus[] = $menu;
            }

            if (count($docsMenus) > 0) {
                return $this->sendResponse($docsMenus, __('messages.success'));
            } else {
                return $this->sendResponse([], __("messages.data_not_found"), HTTP_NOT_FOUND);
            }
        } catch (Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }

    }


    private function prepareSubMenu($subcategories)
    {
        $docsMenus = [];

        foreach ($subcategories as $category) {

            $menu = [
                'title' => $category->name,
                'slug' => $category->slug,
                'link_prefix' => '/library/' . $category->slug
            ];

            if ($category->children->count() > 0) {
                $menu['children'] = $this->prepareSubMenu($category->children);
            }

            $docsMenus[] = $menu;
        }

        return $docsMenus;
    }
}
