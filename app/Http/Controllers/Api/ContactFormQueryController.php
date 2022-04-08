<?php

namespace App\Http\Controllers\Api;

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
     *      operationId="store",
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
    public function store(Request $request) 
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|min:3|max:100',
                'email' => 'required|email',
                'subject' => 'required|min:5|max:100',
                'message' => 'required|min:5|max:255',
            ]);
     
            if ($validator->fails()) {
                return $this->sendError("Error", ['errors' => $validator->errors()], 400);
            }

            $contactPageQuery = ContactPageQuery::create($request->all());
            
            (getAdmins()->first())->notify(new ContactFormQueryReceived($contactPageQuery));

            return $this->sendResponse([], "Query Submitted Successfully");
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        }
    }
}
