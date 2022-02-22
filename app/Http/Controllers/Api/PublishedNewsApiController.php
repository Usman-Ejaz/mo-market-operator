<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;

class PublishedNewsApiController extends BaseApiController
{

    /**
     * 
     * @OA\Tag(
     *     name="News",
     *     description="API Endpoints of News"
     * )
     * 
     */ 

    /** 
     * @OA\Get(
     *      path="/get-news",
     *      operationId="getPublishedNews",
     *      tags={"News"},
     *      summary="Get list of Published News",
     *      description="Returns list of News",
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
    public function getPublishedNews() {
        try {
            $posts = News::published()->latest()->get();

            if ($posts->count() > 0) {
                return $this->sendResponse($posts, "Success");
            } else {
                return $this->sendResponse([], "Could not find news.");
            }
        } catch (\Exception $e) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $e->getMessage()], 500);
        }
    }

    /**
     * 
     * @OA\Get(
     *      path="/show-news/{slug}",
     *      operationId="getSingleNews",
     *      tags={"News"},
     *      summary="Get Specific News against slug",
     *      description="Returns single News",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="slug",
     *          description="News slug",
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
    public function getSingleNews ($slug) {
        try {
            $post = News::published()->where("slug", "=", $slug)->first();

            if ($post) {
                return $this->sendResponse($post, "Success");
            } else {
                return $this->sendResponse([], "Could not found news");
            }
        } catch (\Exception $e) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $e->getMessage()], 402);
        }
    }
}
