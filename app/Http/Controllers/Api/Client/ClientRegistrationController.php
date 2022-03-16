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
     * 
     * @OA\Tag(
     *     name="Clients",
     *     description="API Endpoints of Clients"
     * )
     * 
     */ 


    /**
     * 
     * @OA\Post(
     *      path="/auth/register",
     *      operationId="register",
     *      tags={"Clients"},
     *      summary="Register Client",
     *      description="This API will register the client in the resource and will return a token, This token will be used to proceed next API calls for succesfull client registration.",
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      title="Name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="address",
     *                      title="Address",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="type",
     *                      title="Type",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="categories",
     *                      title="Categories",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="pri_name",
     *                      title="Primary Name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="pri_email",
     *                      title="Primary Email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="pri_address",
     *                      title="Primary Address",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="pri_telephone",
     *                      title="Primary Telephone",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="pri_facsimile_telephone",
     *                      title="Primary Facsimile Telephone",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="sec_name",
     *                      title="Secondary Name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="sec_email",
     *                      title="Secondary Email",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="sec_address",
     *                      title="Secondary Address",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="sec_telephone",
     *                      title="Secondary Telephone",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="sec_facsimile_telephone",
     *                      title="Secondary Facsimile Telephone",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="pri_signature",
     *                      title="Primary Signature",
     *                      type="file"
     *                  ),
     *                  @OA\Property(
     *                      property="sec_signature",
     *                      title="Secondary Signature",
     *                      type="file"
     *                  ),
     *                  required={"name", "address", "type", "categories", "pri_name", "pri_email", "pri_address", "pri_telephone", "pri_facsimile_telephone", "sec_name", "sec_email", "sec_address", "sec_telephone", "sec_facsimile_telephone", "pri_signature", "sec_signature"},
     *                  example={
     *                      "name": "John Doe", 
     *                      "address": "USA",
     *                      "type": "service_provider",
     *                      "categories": "2,3,4",
     *                      "pri_name": "John",
     *                      "pri_email": "johndoe@gmail.com", 
     *                      "pri_address": "USA, California",
     *                      "pri_telephone": "03001234567", 
     *                      "pri_facsimile_telephone": "03001234567",
     *                      "sec_name": "Doe", 
     *                      "sec_email": "johndoe@gmail.com", 
     *                      "sec_address": "USA, California",
     *                      "sec_telephone": "03001234567", 
     *                      "sec_facsimile_telephone": "03001234567",
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
    
    /**
     * getAttributes
     *
     * @return array
     */
    private function getAttributes(): array {
        return [
            'pri_email' => 'primary email'
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
    
    /**
     * saveSignatures
     *
     * @param  mixed $file
     * @return void
     */
    private function saveSignatures($file) {
        return storeFile(Client::SIGNATURE_DIR, $file, null);
    }
}
