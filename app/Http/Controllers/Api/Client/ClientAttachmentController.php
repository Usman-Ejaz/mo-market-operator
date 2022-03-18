<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\ClientAttachment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClientAttachmentController extends BaseApiController
{    

    /**
     * 
     * @OA\Post(
     *      path="/upload-attachments",
     *      operationId="store",
     *      tags={"Clients"},
     *      summary="Upload Client's Document",
     *      description="Upload document in the resource",
     *      security={{"BearerToken": {}}},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="category",
     *                      title="category",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="phrase",
     *                      title="phrase",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="attachment",
     *                      title="attachment",
     *                      type="file"
     *                  ),
     *                  required={"phrase", "attachment"}
     *             )
     *         )
     *      ),
     * 
     *      @OA\Response(
     *          response=200,
     *          description="Successful operation"          
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *  )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'attachment' => 'required|file|max:5000',
            'category' => 'sometimes|string',
            'phrase' => 'required|string'
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
                'category_id' => $request->category ?? null,
                'phrase' => strtolower($request->phrase),
                'client_id' => $request->user()->id
            ]);
            return $this->sendResponse([], "Attachment uploaded successfully");
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }

    /**
     * 
     * @OA\Post(
     *      path="/remove-attachments",
     *      operationId="destroy",
     *      tags={"Clients"},
     *      summary="Remove Client's Document",
     *      description="Remove document in the resource",
     *      security={{"BearerToken": {}}},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="category",
     *                      title="category",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="phrase",
     *                      title="phrase",
     *                      type="string"
     *                  ),
     *                  required={"category", "phrase"},
     *                  example={
     *                      "category": "2",
     *                      "phrase": "test"
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
     *          response=401,
     *          description="Unauthorized",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *  )
     */
    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category' => 'sometimes|string',
            'phrase' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->sendError("Error", ['errors' => $validator->errors()], 400);
        }

        try {
            $attachment = ClientAttachment::findRecord($request->user()->id, $request->category ?? null, $request->phrase)->first();

            if ($attachment) {
                removeFile(ClientAttachment::DIR, $attachment->file);
                $attachment->delete();
                return $this->sendResponse([], "Attachment removed successfully");
            } else {
                return $this->sendError('Error', ["errors" => 'Could not find the record'], 404);
            }
            
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
}
