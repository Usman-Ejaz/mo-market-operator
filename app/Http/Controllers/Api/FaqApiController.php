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
     *      operationId="show",
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
    public function show()
    {
        try {
            $faqs = Faq::published()->oldest()->get();
        
            if ($faqs->count() > 0) {
                return $this->sendResponse($faqs, "Found");
            } else {
                return $this->sendError("Could not found faqs.", ["errors" => "Could not found faqs."], 404);
            }
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
}
