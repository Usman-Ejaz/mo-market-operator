<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Client;
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
            'attachment' => 'required|file|mimes:pdf,docx,doc|max:' . (config('settings.maxDocumentSize') * 4),
            'category' => 'nullable|string|min:3',
            'phrase' => 'required|string|min:3'
        ], [
            'attachment.max' => __('messages.max_file', ['limit' => '20 MB'])
        ]);

        if ($validator->fails()) {
            return $this->sendResponse($validator->errors(), __('messages.error'), HTTP_BAD_REQUEST);
        }

        try {
            $filename = storeFile(ClientAttachment::DIR, $request->file('attachment'));

            if (! $request->has('category') || $request->category === null || $request->category === "") {
                $categoryId = null;
            } else {
                $categoryId = array_search($request->category, Client::REGISTER_CATEGORIES);
                if (! $categoryId) {
                    $categoryId = null;
                }
            }

            ClientAttachment::create([
                'file' => $filename,
                'category_id' => $categoryId,
                'phrase' => strtolower($request->phrase),
                'client_id' => $request->user()->id
            ]);

            return $this->sendResponse(null, __("Attachment uploaded successfully"));
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
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
            'category' => 'nullable|string',
            'phrase' => 'required|string'
        ]);

        if ($validator->fails()) {
            return $this->sendResponse($validator->errors(), __('messages.error'), HTTP_BAD_REQUEST);
        }

        if (! $request->has('category') || $request->category === null || $request->category === "") {
            $categoryId = null;
        } else {
            $categoryId = array_search($request->category, Client::REGISTER_CATEGORIES);
            if (! $categoryId) {
                $categoryId = null;
            }
        }

        try {
            $attachment = ClientAttachment::findRecord($request->user()->id, $categoryId, $request->phrase)->first();

            if ($attachment) {
                removeFile(ClientAttachment::DIR, $attachment->file);
                $attachment->delete();
                return $this->sendResponse(null, __("Attachment removed successfully"));
            } else {
                return $this->sendResponse(null, __('messages.data_not_found'), HTTP_NOT_FOUND);
            }

        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }
}
