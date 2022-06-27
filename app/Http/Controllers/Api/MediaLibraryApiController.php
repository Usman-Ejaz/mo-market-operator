<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaLibraryResource;
use App\Models\MediaLibrary;
use App\Models\MediaLibraryFile;
use Illuminate\Http\Request;

class MediaLibraryApiController extends BaseApiController
{
    /**
     *
     * @OA\Tag(
     *     name="Media Library",
     *     description="API Endpoints of Media Library"
     * )
     *
     * @OA\Get(
     *      path="/media-libraries",
     *      operationId="mediaLibraryList",
     *      tags={"Media Library"},
     *      summary="Get list of Media Libraries with featured image",
     *      description="Returns Media Libraries with featured image",
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
    public function mediaLibraryList()
    {
        try {
            $mediaFiles = MediaLibraryFile::featuredImage()->with('mediaLibrary')->filterRecords()->select("id", "file", "media_library_id")->get();

            if ($mediaFiles->count() > 0) {
                return $this->sendResponse(MediaLibraryResource::collection($mediaFiles), __("messages.success"));
            } else {
                return $this->sendResponse([], __('messages.data_not_found'), HTTP_NOT_FOUND);
            }
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }

    /**
     * @OA\Get(
     *      path="/media-libraries/{slug}",
     *      operationId="mediaFiles",
     *      tags={"Media Library"},
     *      summary="Get list of Media Library files",
     *      description="Returns Media Library files",
     *      security={{"BearerAppKey": {}}},
     *
     *      @OA\Parameter(
     *          name="slug",
     *          description="Media Library slug",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
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
    public function mediaFiles($slug)
    {
        if ($slug === null || $slug === "" || $slug === true || $slug === "true" || $slug === false || $slug === "false") {
            return $this->sendError('error', ['errors' => 'slug is missing']);
        }

        try {
            $mediaLibrary = MediaLibrary::whereSlug($slug)->select("id", "name", "slug", "description", "directory")->first();

            if ($mediaLibrary) {
                $mediaLibrary->mediaFiles = $mediaLibrary->files();
                return $this->sendResponse($mediaLibrary, __('messages.success'));
            } else {
                return $this->sendResponse([], __('messages.data_not_found'), HTTP_NOT_FOUND);
            }
        } catch (\Exception $ex) {
            return $this->sendResponse(['errors' => $ex->getMessage()], __('messages.something_wrong'), HTTP_SERVER_ERROR);
        }
    }
}
