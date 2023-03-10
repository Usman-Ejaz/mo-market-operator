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
            return $this->sendResponse(null, __('slug field is missing.'), HTTP_BAD_REQUEST);
        }

        try {

            if (request()->has('unpublished') && request()->query('unpublished') == 'true') {
                $post = Page::with('author:id,name')->where("slug", "=", $pageSlug)->select('title', 'slug', 'keywords', 'description', 'image', 'created_by')->first();
            } else {
                $post = Page::with('author:id,name')->published()->where("slug", "=", $pageSlug)->first();
            }

            if ($post) {
                return $this->sendResponse($post, __('messages.success'));
            } else {
                return $this->sendResponse(null, __("messages.data_not_found"), HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return $this->sendResponse(["errors" => $e->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }
}
