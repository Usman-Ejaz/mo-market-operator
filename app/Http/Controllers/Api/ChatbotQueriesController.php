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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
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
            'company' => 'sometimes|string',
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
     * askQuestion
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
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

            $chatbotAnswer = ChatBotKnowledgeBase::searchByKeyword($request->question)->first();
            
            if ($chatbotAnswer) {
                // 1. log question with answer against initiatorID in the DB.
                // 2. send email to ISMO staff specified emails
                // 3. send response to client with answer.

                $history = ChatbotHistory::create([
                    'question' => $request->question,
                    'answer' => $chatbotAnswer->answer,
                    'chatbot_initiator_id' => $initiator->id
                ]);

                // $initiator->send_chat_history && event(new NewChatbotQueryArrived($history, $initiator));

                return $this->sendResponse(['answer' => $chatbotAnswer->answer], 'success');
            }

            return $this->sendResponse($initiator, 'answer not found', 203);
            // send initiator details with status code 203.
        } catch (\Exception $ex) {
            return $this->sendError(__('messages.something_wrong'), ['errors' => $ex->getMessage()], 500);
        }
    }

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
