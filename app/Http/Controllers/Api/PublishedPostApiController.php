<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaLibraryResource;
use App\Models\DocumentCategory;
use App\Models\MediaLibrary;
use App\Models\MediaLibraryFile;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
     *      path="/news-and-blogs",
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
            $posts = Post::published()->newsAndBlogs()->latest()->get();

            if ($posts->count() > 0) {
                return $this->sendResponse($posts, "Success");
            } else {
                return $this->sendResponse([], __("messages.data_not_found"));
            }
        } catch (\Exception $e) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $e->getMessage()], 500);
        }
    }    

    /** 
     * @OA\Get(
     *      path="/announcements",
     *      operationId="getPublishedAnnouncements",
     *      tags={"Posts"},
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
    public function getPublishedAnnouncements() {
        try {
            $posts = Post::published()->announcements()->latest()->get();

            if ($posts->count() > 0) {
                return $this->sendResponse($posts, "Success");
            } else {
                return $this->sendResponse([], __("messages.data_not_found"));
            }
        } catch (\Exception $e) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $e->getMessage()], 500);
        }
    } 

    /**
     * 
     * @OA\Get(
     *      path="/post-menu",
     *      operationId="postMenus",
     *      tags={"Posts"},
     *      summary="Get post categories menu list",
     *      description="Get post categories menu list",
     *      security={{"BearerAppKey": {}}},
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
    public function postMenus()
    {
        try {
            // These are the post categories, 
            // These will be dynamic when we create little module for it.
            // Currently, these categories are being handled from App/Models/Post Model
            $categories = [1 => 'News', 2 => 'Blogs', 3 => 'Announcements'];

            $menus = [];
            foreach ($categories as $menuItem) {
                $slug = Str::slug($menuItem);
                $menus[] = [
                    'title' => $menuItem,
                    'slug' => $slug,
                    'link_prefix' => '/' . $slug
                ];
            }

            $docsCategories = DocumentCategory::select('slug', 'name')->get();

            $docsMenus = [];
            foreach ($docsCategories as $category) {
                $docsMenus[] = [
                    'title' => $category->name,
                    'slug' => $category->slug,
                    'link_prefix' => '/' . $category->slug
                ];
            }

            $menus[] = [
                'title' => 'Publications',
                'slug' => 'publications',
                'link_prefix' => '/publications',
                'children' => $docsMenus
            ];

            $menus[] = [
                'title' => 'Events',
                'slug' => 'media-library',
                'link_prefix' => '/media-library',
            ];

            return $this->sendResponse($menus, 'success');
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }

    /**
     * 
     * @OA\Get(
     *      path="/posts/{category}",
     *      operationId="getPostsByCategory",
     *      tags={"Posts"},
     *      summary="Get post categories menu list",
     *      description="Get post categories menu list",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="category",
     *          description="Post category listed in post menu",
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
    public function getPostsByCategory($category)
    {
        try {
            $posts = Post::published()->$category()->applyFilters()->get();

            return $this->sendResponse($posts, 'success');
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }

    /**
     * 
     * @OA\Get(
     *      path="/posts/{category}/{slug}",
     *      operationId="getSinglePost",
     *      tags={"Posts"},
     *      summary="Get Specific Post against slug",
     *      description="Returns single Post",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="category",
     *          description="Post category",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
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
    public function getSinglePost ($category, $slug) 
    {
        if ($category === null || $category === "") {
            return $this->sendError('error', ["errors" => 'category field is missing.'], 500);
        }

        if ($slug === null || $slug === "") {
            return $this->sendError('error', ["errors" => 'slug field is missing.'], 500);
        }

        try {
            // if (request()->has('unpublished') && request()->query('unpublished') == 'true') {
                // $post = Post::$category()->where("slug", "=", $slug)->first();
            // } else {
                $post = Post::published()->$category()->where("slug", "=", $slug)->first();
            // }

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
