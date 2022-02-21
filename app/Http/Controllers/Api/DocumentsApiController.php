<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentsApiController extends BaseApiController
{

    /**
     * 
     * @OA\Tag(
     *     name="Documents",
     *     description="API Endpoints of Documents"
     * )
     * 
     */ 

    /** 
     * @OA\Get(
     *      path="/get-documents",
     *      operationId="getPublishedDocs",
     *      tags={"Documents"},
     *      summary="Get list of Published Documents",
     *      description="Returns list of Documents",
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
    public function getPublishedDocs()
    {
        try {
            $docs = Document::published()->with('category')->latest()->get();

            if ($docs->count() > 0) {
                return $this->sendResponse(DocumentResource::collection($docs), "Success");
            } else {
                return $this->sendError("Could not found documents", ["errors" => "Could not found documents"], 404);
            }
        } catch (\Exception $ex) {
            return $this->sendError("Something went wrong.", ["errors" => $ex->getMessage()], 500);
        }
    }


    /**
     * 
     * @OA\Post(
     *      path="/search-document?key=",
     *      operationId="search",
     *      tags={"Documents"},
     *      summary="Search Document from the resource",
     *      description="Search Document from the resource",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="key",
     *          description="Search Key",
     *          required=true,
     *          in="query",
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
    public function search(Request $request)
    {
        try {
            $docs = Document::published()->latest();
            $searchKey = $request->get("key");

            $docs = $docs->where('title', 'like', "%{$searchKey}%")
                        ->orWhere('keywords', 'like', "%{$searchKey}%")
                        ->get();

            if ($docs->count() > 0) {
                return $this->sendResponse(DocumentResource::collection($docs), "Success");
            } else {
                return $this->sendError("Could not found documents", ["errors" => "Could not found documents"], 404);
            }
        } catch (\Exception $ex) {
            return $this->sendError("Something went wrong.", ["errors" => $ex->getMessage()], 402);
        }
    }
}
