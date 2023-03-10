<?php

namespace App\Http\Controllers\Api;

use App\Events\SiteSearchEvent;
use App\Http\Controllers\Controller;
use App\Http\Resources\DocumentResource;
use App\Http\Resources\SiteSearchResource;
use App\Models\Document;
use App\Search\SitewideSearch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SiteSearchApiController extends BaseApiController
{

    const SEARCH_TYPE_DOC = "documents";
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
     *      description="This search API will work for FAQs, Posts, Jobs, Trainings and Pages when 'type' property is not set or 'type' property is set to 'web_content'. If the 'type' property is set to 'document' then the API will only search from the Documents Module",
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
                return $this->sendResponse($validator->errors(), __('messages.error'), HTTP_BAD_REQUEST);
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

            if ($request->has('sort')) {
                $result = $result->sortBy(function($item) {
                    return $item->published_at;
                }, 0, $request->get('sort') === 'desc');
            }

            if ($request->has('month')) {
                $result = $result->filter(function($item) use ($request) {
                    $month = Carbon::parse(parseDate($item->created_at))->month;
                    return $month === intval($request->get('month'));
                });
            }

            if ($result->count() > 0) {
                return $this->sendResponse(SiteSearchResource::collection($result), __('messages.success'));
            } else {
                return $this->sendResponse([], __('messages.data_not_found'), HTTP_NOT_FOUND);
            }

        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }

    private function searchFromDocuments ($keyword) {

        try {
            $docs = Document::published()->applyFilters(request());
            $docs = $docs->where('title', 'like', "%{$keyword}%")
                        ->orWhere('keywords', 'like', "%{$keyword}%")
                        ->whereHas('category')
                        ->orWhereHas('category', fn ($q) => $q->where('name', 'like' , "%{$keyword}%"))
                        ->get();

            if ($docs->count() > 0) {
                return $this->sendResponse(DocumentResource::collection($docs), __('messages.success'));
            } else {
                return $this->sendResponse([], __('messages.data_not_found'), HTTP_NOT_FOUND);
            }
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }
}
