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
            return $this->sendError("Error", ['errors' => $validator->errors()], 400);
        }

        try {
            $data = $request->all();
            $initiator = ChatbotInitiator::findByEmail($data['email'])->first();

            if ($initiator) {
                return $this->sendResponse(['key' => $initiator->token], 'success');
            }

            $data['token'] = Str::random(30);
            $initiator = ChatbotInitiator::create($data);

            return $this->sendResponse(['key' => $initiator->token], 'success');
        } catch (\Exception $ex) {
            return $this->sendError("error", ["errors" => $ex->getMessage()], 500);
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
            return $this->sendError('error', ['errors' => 'Initiator key is missing.'], 400);
        }

        try {
            $initiator = ChatbotInitiator::findByKey($initiatorKey)->select('name', 'email', 'company', 'phone', 'id')->first();

            if (!$initiator) {
                return $this->sendError('error', ['errors' => 'Chatbot initiator could not find.'], 404);
            }

            $questions = ChatBotKnowledgeBase::select('question', 'answer', 'keywords')->get();
            $chatbotAnswer = null;

            foreach ($questions as $knowledgebase) {
                similar_text(strtolower($request->question), strtolower($knowledgebase->question), $question_similarity);
                similar_text(strtolower($request->question), strtolower($knowledgebase->keywords), $keyword_similarity);
                
                if ((number_format($question_similarity, 0) > 80) || (number_format($keyword_similarity, 0) >= 50)) {
                    $chatbotAnswer = $knowledgebase;
                    break;
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

                return $this->sendResponse(['answer' => $chatbotAnswer->answer], 'success');
            }

            return $this->sendError(__('messages.error'), ['errors' => __('messages.data_not_found')], 404);
            // send initiator details with status code 203.
        } catch (\Exception $ex) {
            return $this->sendError(__('messages.something_wrong'), ['errors' => $ex->getMessage()], 500);
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
            return $this->sendError('error', ['errors' => 'Initiator key is missing.'], 400);
        }

        try {
            $initiator = ChatbotInitiator::findByKey($initiatorKey)->select('name', 'email', 'id', 'send_chat_history')->first();
            $chatHistory = ChatbotHistory::todaysChat($initiator->id)->get();

            if ($chatHistory->count() > 0) {
                event(new ChatbotChatHistoryEvent($chatHistory, $initiator));
            }

            return $this->sendResponse([], 'success');

        } catch (\Exception $ex) {
            return $this->sendError('error', ['errors' => $ex->getMessage()], 500);
        }
    }
}
