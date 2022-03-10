<?php

namespace App\Http\Controllers\Api;

use App\Events\SiteSearchEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\SiteSearchResource;
use App\Models\Document;
use App\Search\SitewideSearch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiteSearchApiController extends BaseApiController
{

    const SEARCH_TYPE_DOC = "document";
    const SEARCH_TYPE_WEB = "web_content";

     /**
     * 
     * @OA\Tag(
     *     name="Site Search",
     *     description="API Endpoints of Global Site Search"
     * )
     * 
     */ 

    /**
     * 
     * @OA\Post(
     *      path="/search",
     *      operationId="siteSearch",
     *      tags={"Site Search"},
     *      summary="Search records from the whole site. (FAQs, Jobs, Documents, Posts, Pages)",
     *      description="This search API will work for FAQs, Posts, Jobs and Pages when 'type' property is not set or 'type' property is set to 'web_content'. If the 'type' property is set to 'document' then the API will only search from the Documents Module",
     *      security={{"BearerAppKey": {}}},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *              mediaType="application/json",
     *               @OA\Schema(
     *                  @OA\Property(
     *                      property="key",
     *                      title="key",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="type",
     *                      title="type",
     *                      type="string"
     *                  ),
     *                  required={"key"},
     *                  example={
     *                      "key": "John Doe",
     *                      "type": ""
     *                  }
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"          
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
    public function search(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'key' => 'required',
                'type' => 'sometimes|bail|string|in:' . self::SEARCH_TYPE_DOC . ',' . self::SEARCH_TYPE_WEB,
            ]);
    
            if ($validator->fails()) {
                return $this->sendError("Error", ['errors' => $validator->errors()], 400);
            }
    
            $keyword = $request->key;
            
            // Log search keywords
            event(new SiteSearchEvent($keyword));

            // Searching for documents
            if ($request->has("type") && $request->type === self::SEARCH_TYPE_DOC) {
                return $this->searchFromDocuments($keyword);
            }

            // Searching with algolia search, upto 10k requests/month in FREE plan.
            $result = SitewideSearch::search($keyword)->get()->where('published_at', '!=', null);

            if ($result->count() > 0) {
                return $this->sendResponse(SiteSearchResource::collection($result), "Success");
            } else {
                return $this->sendResponse([], "Data not found");
            }

        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }        
    }

    private function searchFromDocuments ($keyword) {

        try {
            $docs = Document::published()->latest();
            $docs = $docs->where('title', 'like', "%{$keyword}%")
                        ->orWhere('keywords', 'like', "%{$keyword}%")
                        ->whereHas('category')
                        ->orWhereHas('category', fn ($q) => $q->where('name', 'like' , "%{$keyword}%"))
                        ->get();

            if ($docs->count() > 0) {
                return $this->sendResponse(SiteSearchResource::collection($docs), "Success");
            } else {
                return $this->sendResponse([], "Data not found");
            }
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
}
