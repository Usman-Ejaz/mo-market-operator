<?php

namespace App\Http\Controllers\Api;

use App\Events\ChatbotChatHistoryEvent;
use App\Http\Controllers\Controller;
use App\Models\ChatbotHistory;
use App\Models\ChatbotInitiator;
use App\Models\ChatBotKnowledgeBase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ChatbotQueriesController extends BaseApiController
{
    /**
     *
     * @OA\Tag(
     *     name="Chatbot Queries",
     *     description="API Endpoints of Chatbot Queries"
     * )
     *
     *
     * @OA\Post(
     *      path="/save-chat-initiator-details",
     *      operationId="storeInitiatorDetails",
     *      tags={"Chatbot Queries"},
     *      summary="save chat initiator details",
     *      description="save chat initiator details in the resource",
     *      security={{"BearerAppKey": {}}},
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      title="Name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      title="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="phone",
     *                      title="phone",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="company",
     *                      title="company",
     *                      type="string"
     *                  ),
     *                  required={"name", "email", "phone"},
     *                  example={
     *                      "name": "John Doe",
     *                      "email": "johndoe@email.com",
     *                      "phone": "03001234567",
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
    public function storeInitiatorDetails(Request $request)
    {
        $validator = Validator::make($request->all(), $this->getRules(), $this->getMessages());

        if ($validator->fails()) {
            return $this->sendResponse($validator->errors(), __('messages.error'), HTTP_BAD_REQUEST);
        }

        try {
            $data = $request->all();
            $initiator = ChatbotInitiator::findByEmail($data['email'])->first();

            if ($initiator) {
                return $this->sendResponse(['key' => $initiator->token], __('messages.success'));
            }

            $data['token'] = Str::random(30);
            $initiator = ChatbotInitiator::create($data);

            return $this->sendResponse(['key' => $initiator->token], __('messages.success'));
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }

    /**
     * getRules
     *
     * @return array
     */
    private function getRules()
    {
        return [
            'name' => 'required|string|min:3',
            'email' => 'required|email|string',
            'phone' => 'required',
            'company' => 'sometimes',
            'send_chat_history' => 'sometimes|boolean'
        ];
    }

    /**
     * getMessages
     *
     * @return array
     */
    private function getMessages()
    {
        return [

        ];
    }




    /**
     *
     * @OA\Post(
     *      path="/chatbot-query",
     *      operationId="askQuestion",
     *      tags={"Chatbot Queries"},
     *      summary="Submit asked query and matches it",
     *      description="Submit asked query and matches it in the resource",
     *      security={{"BearerAppKey": {}}},
     *
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="question",
     *                      title="question",
     *                      type="string"
     *                  ),
     *                  required={"question"},
     *                  example={
     *                      "question": "Is this a question?",
     *                  }
     *             )
     *         )
     *      ),
     *      @OA\Parameter(
     *          name="x-initiator-key",
     *          description="Chat initiator unique token",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string"
     *          )
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
    public function askQuestion(Request $request)
    {
        $initiatorKey = $request->header('x-initiator-key');

        if ($initiatorKey === null || $initiatorKey === "") {
            return $this->sendResponse(__('Initiator key is missing.'), HTTP_NOT_FOUND);
        }

        try {
            $initiator = ChatbotInitiator::findByKey($initiatorKey)->select('id')->first();

            if (!$initiator) {
                return $this->sendResponse(__('Chatbot initiator could not find.'), HTTP_NOT_FOUND);
            }

            $questions = ChatBotKnowledgeBase::select('question', 'answer', 'keywords')->get();
            $chatbotAnswer = null;

            foreach ($questions as $knowledgebase) {
                similar_text(strtolower($request->question), strtolower($knowledgebase->question), $question_similarity);

                if ((number_format($question_similarity, 0) >= 70)) {
                    $chatbotAnswer = $knowledgebase;
                    break;
                }
            }

            if ($chatbotAnswer === null) {
                foreach ($questions as $knowledgebase) {
                    $keywords = explode(",", $knowledgebase->keywords);
                    foreach ($keywords as $keyword) {
                        similar_text(strtolower($request->question), strtolower($keyword), $keyword_similarity);

                        if ((number_format($keyword_similarity, 0) >= 90)) {
                            $chatbotAnswer = $knowledgebase;
                            break;
                        }
                    }
                }
            }

            if ($chatbotAnswer) {
                // 1. log question with answer against initiatorID in the DB.
                // 2. send response to client with answer.

                ChatbotHistory::create([
                    'question' => $request->question,
                    'answer' => $chatbotAnswer->answer,
                    'chatbot_initiator_id' => $initiator->id
                ]);

                return $this->sendResponse(['answer' => $chatbotAnswer->answer], __('messages.success'));
            }

            return $this->sendResponse(__('messages.data_not_found'), HTTP_NOT_FOUND);
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }

    /**
     *
     * @OA\Get(
     *      path="/close-chat",
     *      operationId="sendChatHistoryEmail",
     *      tags={"Chatbot Queries"},
     *      summary="Initimate Client and admin about the chat history",
     *      description="Initimate Client and admin about the chat history by sending emails to them.",
     *      security={{"BearerAppKey": {}}},
     *
     *      @OA\Parameter(
     *          name="x-initiator-key",
     *          description="Chat initiator unique token",
     *          required=true,
     *          in="header",
     *          @OA\Schema(
     *              type="string"
     *          )
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
    public function sendChatHistoryEmail(Request $request)
    {
        $initiatorKey = $request->header('x-initiator-key');

        if ($initiatorKey === null || $initiatorKey === "") {
            return $this->sendResponse(['errors' => ['key' => __('Initiator key is missing.')]], __('messages.error'), HTTP_BAD_REQUEST);
        }

        try {
            $initiator = ChatbotInitiator::findByKey($initiatorKey)->select('name', 'email', 'id', 'send_chat_history')->first();
            $chatHistory = ChatbotHistory::todaysChat($initiator->id)->get();

            if ($chatHistory->count() > 0) {
                event(new ChatbotChatHistoryEvent($chatHistory, $initiator));
            }

            return $this->sendResponse(null, __('messages.success'));

        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }
}
