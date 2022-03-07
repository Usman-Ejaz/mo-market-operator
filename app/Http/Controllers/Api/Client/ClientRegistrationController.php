<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientRegistrationController extends BaseApiController
{
    
    /**
     * register
     *
     * @param  mixed $request
     * @return void
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), $this->getRules(), $this->getMessages());

        if ($validator->fails()) {
            return $this->sendError("Error", ['errors' => $validator->errors()], 400);
        }

        try {
            $client = $this->createClient($request);
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
    
    /**
     * getRules
     *
     * @return array
     */
    private function getRules(): array {
        return [
            'name' => 'required|string|min:3',
            'email' => 'required|email|string|unique:email,clients',
            'address' => 'required|string',
        ];
    }
    
    /**
     * getMessages
     *
     * @return array
     */
    private function getMessages(): array {
        return [
            
        ];
    }

    private function createClient($request)
    {
        
    }
    
    /**
     * uploadAttachment
     *
     * @param Illuminate\Http\Request $request
     * @return void
     */
    public function uploadAttachment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attachment' => 'required|file|max:5000',
            'category' => 'required|string',
            'client_id' => 'required|number'
        ]);

        if ($validator->fails()) {
            return $this->sendError("Error", ['errors' => $validator->errors()], 400);
        }

        try {
            $filename = storeFile(Client::ATTACHMENT_DIR, $request->file('attachment'), null);
            ClientAttachment::create([
                'filename' => $filename,
                'category_id' => $request->category,
                'client_id' => $request->client_id
            ]);
            return $this->sendResponse([], "Attachment uploaded successfully");
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
    
    /**
     * removeAttachment
     *
     * @param Illuminate\Http\Request $request
     * @return void
     */
    public function removeAttachment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string',
            'client_id' => 'required|number'
        ]);

        if ($validator->fails()) {
            return $this->sendError("Error", ['errors' => $validator->errors()], 400);
        }

        try {
            // Get the category id from the specified register categories in the model or grab the id from Clients table.
            $categoryId = 1;
            $attachment = ClientAttachment::where(['client_id' => $request->client_id, 'category_id' => $categoryId])->first();

            if (removeFile(Client::ATTACHMENT_DIR, $attachment->filename)) {
                $attachment->update(['filename' => null]);
                return $this->sendResponse([], "Attachment removed successfully");
            }
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
}
