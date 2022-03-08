<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientAttachment;
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
        $validator = Validator::make($request->all(), $this->getRules(), $this->getMessages(), $this->getAttributes());

        if ($validator->fails()) {
            return $this->sendError("Error", ['errors' => $validator->errors()], 400);
        }

        try {
            $clientToken = $this->createClient($request);
            return $this->sendResponse($clientToken, "Success");
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage(), 'type' => get_class($ex)], 500);
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
            // 'email' => 'required|email|string|unique:email,clients',
            'address' => 'required|string|min:5',
            'type' => 'required|string|in:' . implode(",", Client::TYPE),
            'categories' => 'required|string',
            'pri_name' => 'required|string',
            'pri_address' => 'required|string',
            'pri_telephone' => 'required|string',
            'pri_facsimile_telephone' => 'required|string',
            'pri_email' => 'required|email|unique:clients,pri_email',
            'pri_signature' => 'required|file|image|max:2000',
            'sec_name' => 'required|string',
            'sec_address' => 'required|string',
            'sec_telephone' => 'required|string',
            'sec_facsimile_telephone' => 'required|string',
            'sec_email' => 'required|email',
            'sec_signature' => 'required|file|image|max:2000',
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

    private function getAttributes(): array {
        return [
            'pri_email' => 'Primary Email'
        ];
    }
    
    /**
     * createClient
     *
     * @param  Request $request
     * @return string $token
     */
    private function createClient($request)
    {
        if ($request->has('pri_signature')) {
            $primarySign = $this->saveSignatures($request->file('pri_signature'));
        }

        if ($request->has('sec_signature')) {
            $secondarySign = $this->saveSignatures($request->file('sec_signature'));
        }

        $data = $request->all();
        $data['pri_signature'] = $primarySign;
        $data['sec_signature'] = $secondarySign;

        $client = Client::create($data);
        $token = $client->createToken(__('auth.apiTokenKey'))->accessToken;
        return $token;
    }

    private function saveSignatures($file) {
        return storeFile(Client::SIGNATURE_DIR, $file, null);
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
            'category' => 'required|number',
        ], [
            'attachment.max' => __('messages.max_file', ['limit' => '5 MB'])
        ]);

        if ($validator->fails()) {
            return $this->sendError("Error", ['errors' => $validator->errors()], 400);
        }

        try {
            $filename = storeFile(ClientAttachment::DIR, $request->file('attachment'), null);
            ClientAttachment::create([
                'file' => $filename,
                'category_id' => $request->category,
                'client_id' => $request->user()->id
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
            'category' => 'required|number'
        ]);

        if ($validator->fails()) {
            return $this->sendError("Error", ['errors' => $validator->errors()], 400);
        }

        try {
            $attachment = ClientAttachment::where(['client_id' => $request->user()->id, 'category_id' => $request->category])->first();

            if ($attachment && removeFile(ClientAttachment::DIR, $attachment->file)) {
                $attachment->delete();
                return $this->sendResponse([], "Attachment removed successfully");
            }
            
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
}
