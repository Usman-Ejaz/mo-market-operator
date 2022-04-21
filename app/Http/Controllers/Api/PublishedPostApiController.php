<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaLibraryResource;
use App\Models\MediaLibrary;
use App\Models\MediaLibraryFile;
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
     * 
     * @OA\Get(
     *      path="/news-and-blogs/{slug}",
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
            $post = Post::published()->newsAndBlogs()->where("slug", "=", $slug)->first();

            if ($post) {
                return $this->sendResponse($post, "Success");
            } else {
                return $this->sendResponse([], __("messages.data_not_found"));
            }
        } catch (\Exception $e) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $e->getMessage()], 402);
        }
    }


    /** 
     * @OA\Get(
     *      path="/announcements",
     *      operationId="getPublishedAnnouncements",
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
     *      path="/announcement/{slug}",
     *      operationId="getAnnouncement",
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
    public function getAnnouncement ($slug) {
        try {
            $post = Post::published()->announcements()->where("slug", "=", $slug)->first();

            if ($post) {
                return $this->sendResponse($post, __("messages.success"));
            } else {
                return $this->sendResponse([], __("messages.data_not_found"));
            }
        } catch (\Exception $e) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $e->getMessage()], 402);
        }
    }

    public function listPosts() {

        $categories = [ 1 => 'News', 2 => 'Blogs', 3 => 'Announcements'];
        
        try {
            $responseArr = [];

            foreach ($categories as $key => $value) {
                $type = strtolower($value);
                $responseArr[$type] = Post::published()->$type()->get();
            }
            
            $responseArr['events'] = $this->getMediaFiles();

            return $this->sendResponse($responseArr, __("messages.success"));

        } catch (\Exception $ex) {
            
        }
    }

    public function getMediaFiles()
    {
        $libraries = MediaLibrary::select("id", "name", "description", "directory")->with('mediaFiles:file,media_library_id')->get();

        foreach ($libraries as $mediaLibrary) {
            $files = $mediaLibrary->mediaFiles;
            foreach ($files as $mediaFile) {
                $mediaFile->file = serveFile(MediaLibrary::MEDIA_STORAGE . $mediaLibrary->directory . '/', $mediaFile->file);
                unset($mediaFile->id);
                unset($mediaFile->media_library_id);
            }
            unset($mediaLibrary->directory);
            unset($mediaLibrary->id);
        }

        return $libraries;
    }
}
