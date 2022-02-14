<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Faq;
use Illuminate\Http\Request;

class FaqApiController extends BaseApiController
{
    public function show()
    {
        $faqs = Faq::where("active", "=", 1)->select("question", "answer")->get();
        
        if ($faqs->count() > 0) {
            return $this->sendResponse($faqs, "Found");
        }
        return $this->sendResponse([], "Faqs not found");
    }
}
