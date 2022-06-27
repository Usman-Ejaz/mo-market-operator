<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqApiController extends BaseApiController
{

    /**
     *
     * @OA\Tag(
     *     name="Faqs",
     *     description="API Endpoints of faqs"
     * )
     *
     */

    /**
     * @OA\Get(
     *      path="/faqs",
     *      operationId="showFaqs",
     *      tags={"Faqs"},
     *      summary="Get list of Published faqs",
     *      description="Returns list of faqs",
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
    public function showFaqs()
    {
        try {
            $faqs = Faq::published()->oldest()->get();

            if ($faqs->count() > 0) {
                return $this->sendResponse($faqs, __('messages.success'));
            } else {
                return $this->sendResponse([], __('messages.data_not_found'), HTTP_NOT_FOUND);
            }
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), 500);
        }
    }
}
