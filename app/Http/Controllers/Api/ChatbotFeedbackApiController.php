<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatbotInitiator;
use App\Models\ChatbotFeedback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChatbotFeedbackApiController extends BaseApiController
{

    /**
     *
     * @OA\Tag(
     *     name="Chatbot Rating",
     *     description="API Endpoints of Chatbot Rating"
     * )
     *
     * @OA\Post(
     *      path="/feedback-rating",
     *      operationId="submitFeedback",
     *      tags={"Chatbot Rating"},
     *      summary="Submit Feedback with rating",
     *      description="Submit Feedback with rating in the resource",
     *      security={{"BearerAppKey": {}}},
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="key",
     *                      title="key",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="rating",
     *                      title="rating",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="feedback",
     *                      title="feedback",
     *                      type="string"
     *                  ),
     *                  required={"key", "rating", "feedback"},
     *                  example={
     *                      "key": "John Doe",
     *                      "rating": "2",
     *                      "feedback": "Message"
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
    public function submitFeedback(Request $request)
    {
        $data = $request->all();

        $validator = Validator::make($data, $this->getRules(), $this->getMessages());

        if ($validator->fails()) {
            return $this->sendResponse($validator->errors(), __('messages.error'), HTTP_BAD_REQUEST);
        }

        try {
            $initiator = ChatbotInitiator::findByKey($data['key'])->first();

            if ($initiator) {
                ChatbotFeedback::create([
                    'chatbot_initiator_id' => $initiator->id,
                    'rating' => intval($data['rating']),
                    'feedback' => $data['feedback']
                ]);

                return $this->sendResponse(null, __('Feedback submitted successfully!'));
            } else {
                return $this->sendResponse(null, __('messages.data_not_found'), HTTP_NOT_FOUND);
            }
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }

    /**
     * getRules
     *
     * @return void
     */
    private function getRules()
    {
        return [
            'key' => 'required|string',
            'rating' => 'required',
            'feedback' => 'nullable|string'
        ];
    }

    /**
     * getMessages
     *
     * @return void
     */
    private function getMessages()
    {
        return [
            //
        ];
    }
}
