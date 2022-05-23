<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatbotInitiator;
use App\Models\FeedbackRating;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FeedbackRatingApiController extends BaseApiController
{

    /**
     * 
     * @OA\Tag(
     *     name="Feedback Rating",
     *     description="API Endpoints of Feedback Rating"
     * )
     * 
     * @OA\Post(
     *      path="/feedback-rating",
     *      operationId="submitFeedback",
     *      tags={"Feedback Rating"},
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

        $valdiator = Validator::make($data, $this->getRules(), $this->getMessages());

        if ($valdiator->fails()) {
            return $this->sendError($valdiator->errors(), __('messages.error'), 400);
        }

        try {
            $initiator = ChatbotInitiator::findByKey($data['key'])->first();

            if ($initiator) {
                FeedbackRating::create([
                    'chatbot_initiator_id' => $initiator->id,
                    'rating' => intval($data['rating']),
                    'feedback' => $data['feedback']
                ]);

                return $this->sendResponse(__('messages.success'), __('Feedback submitted successfully!'));
            } else {
                return $this->sendError(__('messages.data_not_found'), [], 404);
            }
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
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
