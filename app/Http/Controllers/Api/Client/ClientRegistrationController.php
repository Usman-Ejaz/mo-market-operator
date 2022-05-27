<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Api\BaseApiController;
use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientAttachment;
use App\Models\ClientDetail;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use PDF;

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
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      title="Name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="business",
     *                      title="business",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="address_line_one",
     *                      title="address_line_one",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="address_line_two",
     *                      title="address_line_two",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="city",
     *                      title="city",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="state",
     *                      title="state",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="zipcode",
     *                      title="zipcode",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="country",
     *                      title="country",
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
     *                  required={
     *                      "name", "business", "address_line_one", "address_line_two", "type", "categories",
     *                      "city", "state", "zipcode", "country", "primary_details"
     *                  },
     *                  example={
     *                      "name": "John Doe", 
     *                      "business": "USA",
     *                      "address_line_one": "USA",
     *                      "address_line_two": "USA",
     *                      "type": "service_provider",
     *                      "categories": "2,3,4",
     *                      "city": "",
     *                      "state": "",
     *                      "zipcode": "",
     *                      "country": "",
     *                      "primary_details": {
     *                          "name": "",
     *                          "email": "",
     *                          "designation": "",
     *                          "address_line_one": "",
     *                          "address_line_two": "",
     *                          "city": "",
     *                          "state": "",
     *                          "zipcode": "",
     *                          "country": "",
     *                          "telephone": "",
     *                          "facsimile_telephone": "",
     *                          "signature": "",
     *                          "type": "",
     *                      }
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
        $client = new Client;
        $validator = Validator::make($request->all(), $this->getRules($request, $client), $this->getMessages(), $this->getAttributes());

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
    private function getRules($request, $client): array {
        return [
            'name' => 'required|string|min:3',
            'type' => 'required|string|in:' . implode(",", Client::TYPE),
            'categories' => 'nullable|string',
            'business' => 'required|string|min:3',
            'address_line_one' => 'required|string|min:3',
            'address_line_two' => 'required|string|min:3',
            'city' => 'required|string|min:3',
            'state' => 'required|string|min:3',
            'zipcode' => 'required|string|min:3',
            'country' => 'required|string|min:3',
            'primary_details.name' => 'required|string|min:3',
            'primary_details.email' => 'required|string|email|unique:client_details,email,' . $client->id . ',client_id',
            'primary_details.address_line_one' => 'required|string|min:3',
            'primary_details.address_line_two' => 'required|string|min:3',
            'primary_details.city' => 'required|string|min:3',
            'primary_details.state' => 'required|string|min:3',
            'primary_details.zipcode' => 'required|string|min:3',
            'primary_details.telephone' => 'required|string|min:3',
            'primary_details.facsimile_telephone' => 'required|string|min:3',
            'primary_details.signature' => 'required|string',
            'primary_details.type' => 'required|string|min:3',


            'secondary_details.name' => [Rule::requiredIf($request->has('secondary_details')), 'string', 'min:3'],
            'secondary_details.email' => [Rule::requiredIf($request->has('secondary_details')), 'string', 'email', 'unique:client_details,email,' . $client->id . ',client_id'],
            'secondary_details.address_line_one' => [Rule::requiredIf($request->has('secondary_details')), 'string', 'min:3'],
            'secondary_details.address_line_two' => [Rule::requiredIf($request->has('secondary_details')), 'string', 'min:3'],
            'secondary_details.city' => [Rule::requiredIf($request->has('secondary_details')), 'string', 'min:3'],
            'secondary_details.state' => [Rule::requiredIf($request->has('secondary_details')), 'string', 'min:3'],
            'secondary_details.zipcode' => [Rule::requiredIf($request->has('secondary_details')), 'string', 'min:3'],
            'secondary_details.telephone' => [Rule::requiredIf($request->has('secondary_details')), 'string', 'min:3'],
            'secondary_details.facsimile_telephone' => [Rule::requiredIf($request->has('secondary_details')), 'string', 'min:3'],
            'secondary_details.signature' => [Rule::requiredIf($request->has('secondary_details')), 'string'],
            'secondary_details.type' => [Rule::requiredIf($request->has('secondary_details')), 'string', 'min:3'],
        ];
    }
    
    /**
     * getMessages
     *
     * @return array
     */
    private function getMessages(): array {
        return [
            // 'primary_details' => [
            // ]
        ];
    }
    
    /**
     * getAttributes
     *
     * @return array
     */
    private function getAttributes(): array {
        return [
            'primary_details.name' => 'name',
            'primary_details.email' => 'email',
            'primary_details.address_line_one' => 'address line one',
            'primary_details.address_line_two' => 'address line two',
            'primary_details.facsimile_telephone' => 'facsimile telephone',

            'secondary_details.name' => 'name',
            'secondary_details.email' => 'email',
            'secondary_details.address_line_one' => 'address line one',
            'secondary_details.address_line_two' => 'address line two',
            'secondary_details.facsimile_telephone' => 'facsimile telephone',
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
    private function storeClientDetails($data, $type, $clientId, $isUpdating = false)
    {
        if (! $isUpdating) {
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
                'signature'             => $this->saveSignatures($data['signature'])
            ]);

            return;
        } else {
            $clientDetails = ClientDetail::where(['client_id' => $clientId, 'type' => $type])->first();

            if ($clientDetails) {
                removeFile(ClientDetail::SIGNATURE_DIR ,$clientDetails->signature);
            }

            $clientDetails->update([
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
                'signature'             => $this->saveSignatures($data['signature'])
            ]);
        }
    }
    
    /**
     * saveSignatures
     *
     * @param  mixed $file
     * @return string | null
     */
    private function saveSignatures($dataURL) 
    {
        list($type, $data) = explode(';', $dataURL);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);
        list(, $extension) = explode('/', $type);
        $filename = Str::random(20) . '.' . $extension;
        Storage::disk('app')->put(ClientDetail::SIGNATURE_DIR . $filename, $data);

        return $filename;
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

    /**
     * 
     * @OA\Post(
     *      path="/confirm-registration",
     *      operationId="confirmRegistration",
     *      tags={"Clients"},
     *      summary="Confirm registration of client",
     *      description="Confirm registration of client.",
     *      security={{"BearerToken": {}}},
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
    public function confirmRegistration(Request $request)
    {
        try {
            $client = Client::find($request->user()->id);
            $client->update(['profile_complete' => 1]);

            return $this->sendResponse([], __('messages.success'));
        } catch (\Exception $ex) {
            return $this->sendError(__('messages.error'), __('messages.something_wrong'), 500);
        }
    }

    /**
     * 
     * @OA\Put(
     *      path="/update-client",
     *      operationId="updateClient",
     *      tags={"Clients"},
     *      summary="Update Client in the resource",
     *      description="Update Client in the resource",
     *      security={{"BearerToken": {}}},
     * 
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                  @OA\Property(
     *                      property="name",
     *                      title="Name",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="business",
     *                      title="business",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="address_line_one",
     *                      title="address_line_one",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="address_line_two",
     *                      title="address_line_two",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="city",
     *                      title="city",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="state",
     *                      title="state",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="zipcode",
     *                      title="zipcode",
     *                      type="string"
     *                  ),
     *                  @OA\Property(
     *                      property="country",
     *                      title="country",
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
     *                  required={
     *                      "name", "business", "address_line_one", "address_line_two", "type", "categories",
     *                      "city", "state", "zipcode", "country", "primary_details"
     *                  },
     *                  example={
     *                      "name": "John Doe", 
     *                      "business": "USA",
     *                      "address_line_one": "USA",
     *                      "address_line_two": "USA",
     *                      "type": "service_provider",
     *                      "categories": "2,3,4",
     *                      "city": "",
     *                      "state": "",
     *                      "zipcode": "",
     *                      "country": "",
     *                      "primary_details": {
     *                          "name": "",
     *                          "email": "",
     *                          "designation": "",
     *                          "address_line_one": "",
     *                          "address_line_two": "",
     *                          "city": "",
     *                          "state": "",
     *                          "zipcode": "",
     *                          "country": "",
     *                          "telephone": "",
     *                          "facsimile_telephone": "",
     *                          "signature": "",
     *                          "type": "",
     *                      }
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
    public function updateClient(Request $request)
    {
        $client = $request->user();

        $validator = Validator::make($request->all(), $this->getRules($request, $client), $this->getMessages(), $this->getAttributes());

        if ($validator->fails()) {
            return $this->sendError("Error", ['errors' => $validator->errors()], 400);
        }

        try {
            $clientToken = $this->update($request);
            return $this->sendResponse($clientToken, __("messages.success"));
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage()], 500);
        } catch (\Exception $ex) {
            return $this->sendError(__("messages.something_wrong"), ["errors" => $ex->getMessage(), 'type' => get_class($ex)], 500);
        }
    }

    private function update($request)
    {
        $data = $request->all();

        $client = $request->user();

        $client->update([
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

        $this->storeClientDetails($data['primary_details'], ClientDetail::PRIMARY, $client->id, true);
        if ($client !== null) {

            if ($request->has('secondary_details')) {
                $this->storeClientDetails($data['secondary_details'], ClientDetail::SECONDARY, $client->id);
            }

            $token = $client->createToken(__('auth.apiTokenKey'))->accessToken;

            return ['token' => $token];
        }
        
        return ['token' => null];
    }

    /**
     * 
     * @OA\Get(
     *      path="/download-application",
     *      operationId="downloadApplication",
     *      tags={"Clients"},
     *      summary="Prepare the PDF document for application under process.",
     *      description="Prepare the PDF document for application under process.",
     *      security={{"BearerToken": {}}},
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
    public function downloadApplication(Request $request)
    {
        try {
            $client = $request->user();
            $primaryDetails = $client->primaryDetails();
            $secondaryDetails = $client->secondaryDetails();

            set_time_limit(300);

            $pdf = PDF::loadView('clients.registration-form-summary', [
                'client' => $client,
                'primaryDetails' => $primaryDetails,
                'secondaryDetails' => $secondaryDetails,
                'generalAttachments' => $client->generalAttachments(),
                'categoryAttachments' => $client->categoryAttachments(),
                'files_count' => $client->attachments->count()
            ]);

            $filename = Str::random(16) . '.PDF';

            Storage::disk('app')->put('clients/forms/' . $filename, $pdf->output());

            $link = serveFile('clients/forms/', $filename);

            return $this->sendResponse($link, __('messages.success'));
        } catch (\Exception $ex) {
            $this->sendError(__('messages.error'), $ex->getMessage(), 500);
        }
        // Storage::put('somepath here with filename', $pdf->output());
    }
}
