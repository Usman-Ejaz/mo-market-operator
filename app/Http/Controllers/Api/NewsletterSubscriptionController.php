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
                'email.unique' => 'This email is already subscribed to newsletters.'
            ]);

            if ($validator->fails()) {
                return $this->sendResponse($validator->errors(), __('messages.error'), HTTP_BAD_REQUEST);
            }

            Subscriber::create($request->all());
            return $this->sendResponse(null, __("Subscribed to newsletters successfully!"));
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }
}
