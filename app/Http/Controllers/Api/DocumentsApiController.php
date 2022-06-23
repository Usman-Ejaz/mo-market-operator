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
     *     name="Publications",
     *     description="API Endpoints of Documents"
     * )
     *
     */

    /**
     * @OA\Get(
     *      path="/documents",
     *      operationId="getPublishedDocs",
     *      tags={"Publications"},
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
            $docs = Document::published()->with('category:id,slug')->applyFilters(request())->get();

            if ($docs->count() > 0) {
                return $this->sendResponse(DocumentResource::collection($docs), "Success");
            } else {
                return $this->sendResponse([], __("messages.data_not_found"));
            }
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }


    /**
     *
     * @OA\Post(
     *      path="/search-document?key=",
     *      operationId="search",
     *      tags={"Publications"},
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
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }


    /**
     *
     * @OA\Get(
     *      path="/publications/{category}",
     *      operationId="getDocumentsByCategory",
     *      tags={"Publications"},
     *      summary="Search Document from the resource against the specified category",
     *      description="Search Document from the resource against the specified category",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Parameter(
     *          name="category",
     *          description="Get publications by category",
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
    public function getDocumentsByCategory($category)
    {
        try {
            $docs = Document::published()->filterByCategory($category)->latest()->applyFilters(request())->get();

            if ($docs->count() > 0) {
                return $this->sendResponse(DocumentResource::collection($docs), "Success");
            } else {
                return $this->sendError(__("messages.error"), ["errors" => __("messages.data_not_found")], 404);
            }
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }

    /**
     *
     * @OA\Get(
     *      path="/publications/{category}/{slug}",
     *      operationId="getSingleDocument",
     *      tags={"Publications"},
     *      summary="Search Document from the resource against the specified category",
     *      description="Search Document from the resource against the specified category",
     *      security={{"BearerAppKey": {}}},
     *
     *      @OA\Parameter(
     *          name="category",
     *          description="Get publications by category",
     *          required=true,
     *          in="path",
     *          @OA\Schema(
     *              type="string"
     *          )
     *      ),
     *
     *      @OA\Parameter(
     *          name="slug",
     *          description="Get publications by slug",
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
    public function getSingleDocument($category, $slug)
    {
        if ($category === null || $category === "") {
            return $this->sendError('error', ["errors" => 'category field is missing.'], 500);
        }

        if ($slug === null || $slug === "") {
            return $this->sendError('error', ["errors" => 'slug field is missing.'], 500);
        }

        try {
            $document = Document::published()->filterByCategory($category)->where('slug', '=', $slug)->first();

            if ($document) {
                return $this->sendResponse(new DocumentResource($document), __('messages.success'));
            } else {
                return $this->sendError(__("messages.error"), ["errors" => __("messages.data_not_found")], 404);
            }
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
}
