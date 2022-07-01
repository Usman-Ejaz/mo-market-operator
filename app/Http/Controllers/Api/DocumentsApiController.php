<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Models\Document;
use App\Models\DocumentCategory;
use Illuminate\Http\Request;

class DocumentsApiController extends BaseApiController
{

    private $categoryIds = null;

    public function __construct() {
        $this->categoryIds = collect();
    }
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
     *      )
     *  )
     */
    public function getPublishedDocs()
    {
        try {
            $docs = Document::published()->with('category:id,slug')->applyFilters(request())->get();

            if ($docs->count() > 0) {
                return $this->sendResponse(DocumentResource::collection($docs), __('messages.success'));
            } else {
                return $this->sendResponse([], __("messages.data_not_found"), HTTP_NOT_FOUND);
            }
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
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
                return $this->sendResponse(DocumentResource::collection($docs), __('messages.success'));
            } else {
                return $this->sendResponse([], __("messages.data_not_found"), HTTP_NOT_FOUND);
            }
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
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

            $category = DocumentCategory::where('slug', '=', $category)->first();

            if ($category) {
                if ($category->children->count() > 0) {
                    $this->setIdsOfNestedChildren($category->children);
                }
                $this->categoryIds->prepend($category->id);
            }

            $docs = Document::published($this->categoryIds)->latest()->applyFilters(request())->get();

            if ($docs->count() > 0) {
                return $this->sendResponse(DocumentResource::collection($docs), __('messages.success'));
            } else {
                return $this->sendResponse([], __("messages.data_not_found"), HTTP_NOT_FOUND);
            }
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
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
            return $this->sendResponse(null, __('category field is missing.'), HTTP_BAD_REQUEST);
        }

        if ($slug === null || $slug === "") {
            return $this->sendResponse(null, __('slug field is missing.'), HTTP_BAD_REQUEST);
        }

        try {
            $document = Document::published()->filterByCategory($category)->where('slug', '=', $slug)->first();

            if ($document) {
                return $this->sendResponse(new DocumentResource($document), __('messages.success'));
            } else {
                return $this->sendResponse(null, __("messages.data_not_found"), HTTP_NOT_FOUND);
            }
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }

    private function setIdsOfNestedChildren($children)
    {
        foreach ($children as $child) {

            if ($child->children->count() > 0) {
                $this->setIdsOfNestedChildren($child->children);
            }
            $this->categoryIds->push($child->id);
        }
    }
}
