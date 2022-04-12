<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PublishedPostApiController extends BaseApiController
{

    /**
     * 
     * @OA\Tag(
     *     name="Posts",
     *     description="API Endpoints of Posts"
     * )
     * 
     * @OA\Tag(
     *     name="Announcements",
     *     description="API Endpoints of Announcements"
     * )
     * 
     */ 

    /** 
     * @OA\Get(
     *      path="/get-posts",
     *      operationId="getPublishedPosts",
     *      tags={"Posts"},
     *      summary="Get list of Published Posts",
     *      description="Returns list of Posts",
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
    public function getPublishedPosts() {
        try {
            $posts = Post::published()->onlyNewsAndBlogs()->latest()->get();

            if ($posts->count() > 0) {
                return $this->sendResponse($posts, "Success");
            } else {
                return $this->sendResponse([], "Could not find posts.");
            }
        } catch (\Exception $e) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $e->getMessage()], 500);
        }
    }

    /**
     * 
     * @OA\Get(
     *      path="/show-post/{slug}",
     *      operationId="getSinglePost",
     *      tags={"Posts"},
     *      summary="Get Specific Post against slug",
     *      description="Returns single Post",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="slug",
     *          description="Post slug",
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
    public function getSinglePost ($slug) {
        try {
            $post = Post::published()->onlyNewsAndBlogs()->where("slug", "=", $slug)->first();

            if ($post) {
                return $this->sendResponse($post, "Success");
            } else {
                return $this->sendResponse([], "Could not found post");
            }
        } catch (\Exception $e) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $e->getMessage()], 402);
        }
    }


    /** 
     * @OA\Get(
     *      path="/get-announcements",
     *      operationId="getPublishedPressReleases",
     *      tags={"Announcements"},
     *      summary="Get list of Published Announcements",
     *      description="Returns list of Announcements",
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
    public function getPublishedPressReleases() {
        try {
            $posts = Post::published()->onlyPressRelease()->latest()->get();

            if ($posts->count() > 0) {
                return $this->sendResponse($posts, "Success");
            } else {
                return $this->sendResponse([], "Could not find posts.");
            }
        } catch (\Exception $e) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $e->getMessage()], 500);
        }
    }

    /**
     * 
     * @OA\Get(
     *      path="/show-announcement/{slug}",
     *      operationId="getPressRelease",
     *      tags={"Announcements"},
     *      summary="Get Specific Announcement against slug",
     *      description="Returns single Announcement",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="slug",
     *          description="Announcement slug",
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
    public function getPressRelease ($slug) {
        try {
            $post = Post::published()->onlyPressRelease()->where("slug", "=", $slug)->first();

            if ($post) {
                return $this->sendResponse($post, "Success");
            } else {
                return $this->sendResponse([], "Could not found post");
            }
        } catch (\Exception $e) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $e->getMessage()], 402);
        }
    }
}
