<?php

namespace App\Http\Controllers\Api;

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
     */ 

    /** 
     * @OA\Get(
     *      path="/sitemap",
     *      operationId="index",
     *      tags={"Sitemap"},
     *      summary="Get list of Published Pages",
     *      description="Returns list of Pages",
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
            $pages = Page::published()->oldest()->get();

            if ($pages->count() > 0) {
                return $this->sendResponse($pages, 'Sitemap retrieved successfully.');
            } else {
                return $this->sendResponse([], "Could not find pages.");
            }
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
}
