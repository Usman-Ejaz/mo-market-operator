<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactPageQuery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactFormQueryController extends BaseApiController
{
    public function store(Request $request) 
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:3|max:100',
            'email' => 'required|email',
            'subject' => 'required|min:5|max:100',
            'message' => 'required|min:5|max:255',
        ]);
 
        if ($validator->fails()) {
            return $this->sendError("Error", ['errors' => $validator->errors()], 401);
        }
        
        ContactPageQuery::create($request->all());
        return $this->sendResponse([], "Query Submitted Successfully", 201);
    }
}
