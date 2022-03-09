<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\ClientAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientAttachmentController extends BaseApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attachment' => 'required|file|max:5000',
            'category' => 'required|string',
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
     * Display the specified resource.
     *
     * @param  \App\Models\ClientAttachment  $clientAttachment
     * @return \Illuminate\Http\Response
     */
    public function show(ClientAttachment $clientAttachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ClientAttachment  $clientAttachment
     * @return \Illuminate\Http\Response
     */
    public function edit(ClientAttachment $clientAttachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ClientAttachment  $clientAttachment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ClientAttachment $clientAttachment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'required|string'
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
