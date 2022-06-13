<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\Http\Request;

class PagesApiController extends BaseApiController
{

    /**
     * 
     * @OA\Tag(
     *     name="Pages",
     *     description="API Endpoints of CMS Pages"
     * )
     */ 

    /**
     * 
     * @OA\Get(
     *      path="/pages/{slug}",
     *      operationId="showPage",
     *      tags={"Pages"},
     *      summary="Get Specific Page against slug",
     *      description="Returns single Page",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="slug",
     *          description="Page slug",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"          
     *       ),
     *      @OA\Response(
     *          response=402,
     *          description="Unauthorized",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *  )
     */
    public function showPage($pageSlug)
    {
        if ($pageSlug === null || $pageSlug === "") {
            return $this->sendError('error', ["errors" => 'slug field is missing.'], 500);
        }

        try {

            if (request()->has('unpublished') && request()->query('unpublished') == 'true') {
                $post = Page::with('author:id,name')->where("slug", "=", $pageSlug)->select('title', 'slug', 'keywords', 'description', 'image', 'created_by')->first();
            } else {
                $post = Page::with('author:id,name')->published()->where("slug", "=", $pageSlug)->first();
            }

            if ($post) {
                return $this->sendResponse($post, "Success");
            } else {
                return $this->sendResponse([], __("messages.data_not_found"));
            }
        } catch (\Exception $e) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $e->getMessage()], 500);
        }
    }
}
