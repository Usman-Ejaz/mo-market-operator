<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\BaseApiController;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NewsletterSubscriptionController extends BaseApiController
{
    public function subscribe(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'bail|required|string|min:3|max:100',
            'email' => 'bail|required|email|string|unique:subscribers,email'
        ], [
            'email.unique' => 'This email is already subscribed to newslettes.'
        ]);

        if ($validator->fails()) {
            return $this->sendError("Error", ['errors' => $validator->errors()], 401);
        }
        
        Subscriber::create($request->all());
        return $this->sendResponse([], "Subscribed to Newsletters Successfully!");
    }
}
