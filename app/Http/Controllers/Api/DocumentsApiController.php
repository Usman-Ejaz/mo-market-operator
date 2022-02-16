<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;

class DocumentsApiController extends BaseApiController
{
    public function getPublishedDocs()
    {
        try {
            $docs = Document::published()->latest()->get();

            if ($docs->count() > 0) {
                return $this->sendResponse($docs, "Success");
            } else {
                return $this->sendError([], "Could not found documents", 404);
            }
        } catch (\Exception $ex) {
            return $this->sendError("Something went wrong.", ["errors" => $ex->getMessage()], 402);
        }
    }

    public function searchDocument(Request $request)
    {
        try {
            $docs = Document::published()->latest()->get();

            if ($docs->count() > 0) {
                return $this->sendResponse($docs, "Success");
            } else {
                return $this->sendError([], "Could not found documents", 404);
            }
        } catch (\Exception $ex) {
            return $this->sendError("Something went wrong.", ["errors" => $ex->getMessage()], 402);
        }
    }
}
