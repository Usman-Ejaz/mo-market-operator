<?php

namespace App\Http\Controllers\Api;

use App\Events\NewContactQueryHasArrived;
use App\Http\Controllers\Controller;
use App\Models\ContactPageQuery;
use App\Notifications\ContactFormQueryReceived;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactFormQueryController extends BaseApiController
{
    /**
     * 
     * @OA\Tag(
     *     name="Contact Us",
     *     description="API Endpoints of Contact Us Form"
     * )
     * 
     */ 

    /**
     * 
     * @OA\Post(
     *      path="/submit-query",
     *      operationId="submit",
     *      tags={"Contact Us"},
     *      summary="Submit Constact Form Query",
     *      description="Submit Constact Form Query in the resource",
     *      security={{"BearerAppKey": {}}},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      title="name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="email",
     *                      title="email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="subject",
     *                      title="subject",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="message",
     *                      title="message",
     *                      type="string"
     *                  ),
     *                  required={"name", "email", "subject", "message"},
     *                  example={
     *                      "name": "John Doe", 
     *                      "email": "johndoe@email.com",
     *                      "subject": "Query Subject",
     *                      "message": "Query Message",
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
     *          response=402,
     *          description="Unauthorized",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      )
     *  )
     */
    public function submit(Request $request) 
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3|max:100',
                'email' => 'required|email',
                'subject' => 'required|min:5|max:100',
                'message' => 'required|min:5|max:255',
                'type' => 'sometimes|string|max:10'
            ]);
     
            if ($validator->fails()) {
                return $this->sendError("Error", ['errors' => $validator->errors()], 400);
            }

            $contactPageQuery = ContactPageQuery::create($request->all());

            event(new NewContactQueryHasArrived($contactPageQuery));
            
            return $this->sendResponse([], __("Query Submitted Successfully"));
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
}
