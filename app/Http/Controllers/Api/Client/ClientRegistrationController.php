<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientAttachment;
use App\Models\ClientDetail;
use Illuminate\Validation\Rule;
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
        $validator = Validator::make($request->all(), $this->getRules($request), $this->getMessages(), $this->getAttributes());

        if ($validator->fails()) {
            return $this->sendError("Error", ['errors' => $validator->errors()], 400);
        }

        try {
            $clientToken = $this->createClient($request);
            return $this->sendResponse($clientToken, __("messages.success"));
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
    private function getRules($request): array {
        return [
            'name' => 'required|string|min:3',
            'type' => 'required|string|in:' . implode(",", Client::TYPE),
            'categories' => 'required|string',
            'business' => 'required|string|min:5',
            'address_line_one' => 'required|string|min:5',
            'address_line_two' => 'required|string|min:5',
            'city' => 'required|string|min:5',
            'state' => 'required|string|min:5',
            'zipcode' => 'required|string|min:5',
            'country' => 'required|string|min:5',
            'primary_details' => 'required',
            'secondary_details' => Rule::requiredIf($request->has('secondary_details')),
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
        $data = $request->all();

        $client = Client::create([
            'name'              => $data['name'],
            'type'              => $data['type'],
            'business'          => $data['business'],
            'address_line_one'  => $data['address_line_one'],
            'address_line_two'  => $data['address_line_two'],
            'city'              => $data['city'],
            'state'	            => $data['state'],
            'zipcode'           => $data['zipcode'],
            'country'           => $data['country'],
            'categories'        => $data['categories']
        ]);

        if ($client !== null) {

            $this->storeClientDetails($data['primary_details'], ClientDetail::PRIMARY, $client->id);

            if ($request->has('secondary_details')) {
                $this->storeClientDetails($data['secondary_details'], ClientDetail::SECONDARY, $client->id);
            }

            $token = $client->createToken(__('auth.apiTokenKey'))->accessToken;

            return ['token' => $token];
        }
        
        return ['token' => null];
    }

    
    /**
     * storeClientDetails
     *
     * @param  array $data
     * @param  string $type
     * @return void
     */
    private function storeClientDetails($data, $type, $clientId)
    {
        ClientDetail::create([
            'client_id'             => $clientId,
            'name'                  => $data['name'],
            'email'                 => $data['email'],
            'designation'           => $data['designation'],
            'type'                  => $type,
            'address_line_one'      => $data['address_line_one'],
            'address_line_two'      => $data['address_line_two'],
            'city'                  => $data['city'],
            'state'	                => $data['state'],
            'zipcode'               => $data['zipcode'],
            'telephone'             => $data['telephone'],
            'facsimile_telephone'   => $data['facsimile_telephone'],
            'signature'             => $this->saveSignatures(request()->file($type . '_signature'))
        ]);
    }
    
    /**
     * saveSignatures
     *
     * @param  mixed $file
     * @return string | null
     */
    private function saveSignatures($file) 
    {
        return storeFile(Client::SIGNATURE_DIR, $file);
    }

    /**
     * @OA\Get(
     *      path="/client-registration-form",
     *      operationId="getRegistrationFormData",
     *      tags={"Clients"},
     *      summary="Get client registration form Data",
     *      description="Get client registration form Data",
     *      security={{"BearerAppKey": {}}},
     *      @OA\Response(
     *          response=200,
     *          description="Success"          
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthorized",
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Could not found",
     *      ),
     *  )
     * 
     */
    public function getRegistrationFormData()
    {
        try {
            return $this->sendResponse([
                'categories' => __('client.categories'),
                'general_keys' => __('client.general_keys'),
                'category_keys' => __('client.keys'),
                'registration_types' => __('client.registration_types')
            ], __('messages.success'));
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage(), 'type' => get_class($ex)], 500);
        }
    }
}
