<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\MediaLibraryResource;
use App\Models\MediaLibrary;
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
     */ 

    /** 
     * @OA\Get(
     *      path="/media-library",
     *      operationId="getFiles",
     *      tags={"Media Library"},
     *      summary="Get list of Media Library files",
     *      description="Returns Media Library files",
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
    public function getFiles()
    {
        try {
            $mediaFiles = MediaLibrary::featuredImage()->get();
        
            if ($mediaFiles->count() > 0) {
                return $this->sendResponse(MediaLibraryResource::collection($mediaFiles), "Found.");
            } else {
                return $this->sendResponse([], "Data not found.");
            }
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
}
