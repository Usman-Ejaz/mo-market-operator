<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterSubscriptionController extends BaseApiController
{

    
    // *                  @OA\Property(
    //     *                      property="name",
    //     *                      title="name",
    //     *                      type="string"
    //     *                  ),

    /**
     * 
     * @OA\Tag(
     *     name="Newsletters",
     *     description="API Endpoints of Newsletters"
     * )
     * 
     */ 

    /**
     * 
     * @OA\Post(
     *      path="/subscribe-to-newsletter",
     *      operationId="subscribe",
     *      tags={"Newsletters"},
     *      summary="Subscribe Newsletter",
     *      description="Subscribe to Newsletters",
     *      security={{"BearerAppKey": {}}},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="email",
     *                      title="email",
     *                      type="string"
     *                  ),
     *                  required={"email"},
     *                  example={
     *                      "email": "johndoe@email.com"
     *                  }
     *             )
     *         )
     *      ),
     * 
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
    public function subscribe(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                // 'name' => 'bail|required|string|min:3|max:100',
                'email' => 'bail|required|email|string|unique:subscribers,email'
            ], [
                'email.unique' => 'This email is already subscribed to newslettes.'
            ]);
    
            if ($validator->fails()) {
                return $this->sendError("Error", ['errors' => $validator->errors()], 401);
            }
            
            Subscriber::create($request->all());
            return $this->sendResponse([], "Subscribed to Newsletters Successfully!");
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }        
    }
}
