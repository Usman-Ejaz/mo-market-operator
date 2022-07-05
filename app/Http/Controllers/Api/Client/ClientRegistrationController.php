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
            return $this->sendResponse($validator->errors(), __('messages.error'), HTTP_BAD_REQUEST);
        }

        try {
            $existingClient = $this->clientExists($request);

            if ($existingClient) {
                $data = $this->getClientInformations($existingClient);

                return $this->sendResponse($data, __('messages.success'));
            }

            if (!$existingClient) {
                $validator = Validator::make($request->all(), [
                    'primary_details.email' => 'required|string|email|unique:client_details,email,' . $client->id . ',client_id'
                ], [], $this->getAttributes());

                if ($validator->fails()) {
                    return $this->sendResponse($validator->errors(), __('messages.error'), HTTP_BAD_REQUEST);
                }

                $clientToken = $this->createClient($request);

                return $this->sendResponse($clientToken, __("messages.success"));
            }


        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage(), 'type' => get_class($ex)], __("messages.something_wrong"), HTTP_SERVER_ERROR);
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
            // 'primary_details.email' => 'required|string|email|unique:client_details,email,' . $client->id . ',client_id',
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
            //
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
        Storage::disk(config('settings.storage_disk'))->put(ClientDetail::SIGNATURE_DIR . $filename, $data);

        return $filename;
    }

    private function clientExists($request)
    {
        $clientInfo = ClientDetail::where(['email' => $request->get('primary_details')['email'], 'type' => ClientDetail::PRIMARY])->first();

        if ($clientInfo !== null) {
            return Client::where(['id' => $clientInfo->client_id, 'profile_complete' => 0])->first();
        }

        return null;
    }

    private function getClientInformations($client)
    {
        $data['general_docs'] = $client->generalAttachments()->map(function ($item) {
            return [
                'file' => $item->file,
                'filename' => getFileOriginalName($item->file),
                'key' => $item->phrase
            ];
        });
        $data['categories_docs'] = $this->getCategoryAttachments($client);
        $data['token'] = $client->createToken(__('auth.apiTokenKey'))->accessToken;

        return $data;
    }

    public function getCategoryAttachments($client)
    {
        $attachments = $client->categoryAttachments();
        $arr = [];
        foreach ($attachments as $key => $items) {
            $arr[Client::REGISTER_CATEGORIES[$key]] = [];

            foreach ($items as $item) {
                $arr[Client::REGISTER_CATEGORIES[$key]][] = [
                    'file' => $item->file,
                    'filename' => getFileOriginalName($item->file),
                    'key' => $item->phrase
                ];
            }
        }
        return $arr;
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
            return $this->sendResponse(["errors" => $ex->getMessage(), 'type' => get_class($ex)], __("messages.something_wrong"), HTTP_SERVER_ERROR);
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
            $validator = Validator::make($request->all(), [
                'dec_name'      => 'required|string',
                'dec_date'      => 'required',
                'dec_signature' => 'required|file|mimes:png,jpg|max:' . config('settings.maxImageSize')
            ], [
                'dec_signature.max' => __('messages.max_file', ['limit' => '2 MB']),
            ], [
                'dec_signature' => 'Declaration signature'
            ]);

            if ($validator->fails()) {
                return $this->sendResponse($validator->errors(), __('messages.error'), HTTP_BAD_REQUEST);
            }

            $client = Client::find($request->user()->id);
            $data = $validator->validate();
            $data['dec_signature'] = $this->saveSignatures($data['dec_signature']);
            $data['profile_complete'] = 1;

            $client->update($data);

            return $this->sendResponse(null, __('messages.success'));
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __('messages.something_wrong'), HTTP_SERVER_ERROR);
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
            return $this->sendResponse($validator->errors(), __('messages.error'), HTTP_BAD_REQUEST);
        }

        try {
            $clientToken = $this->update($request);
            return $this->sendResponse($clientToken, __("messages.success"));
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage(), 'type' => get_class($ex)], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }

    private function update($request)
    {
        $data = $request->all();

        $client = $request->user();

        $this->removeOldCategoryAttachments($client->categories, $data['categories'], $client->id);

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
                $this->storeClientDetails($data['secondary_details'], ClientDetail::SECONDARY, $client->id, true);
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

            Storage::disk(config('settings.storage_disk'))->put('clients/forms/' . $filename, $pdf->output());

            $link = serveFile('clients/forms/', $filename);

            return $this->sendResponse($link, __('messages.success'));
        } catch (\Exception $ex) {
            return $this->sendResponse(["errors" => $ex->getMessage()], __("messages.something_wrong"), HTTP_SERVER_ERROR);
        }
    }


    /**
     * removeOldCategoryAttachments
     *
     * @param  mixed $clientCategories
     * @param  mixed $newCategories
     * @param  mixed $clientId
     * @return string
     */
    private function removeOldCategoryAttachments($clientCategories, $newCategories, $clientId)
    {
        if ($newCategories === null || $newCategories === "") {
            return null;
        }

        $newCategories = explode(",", $newCategories);
        $cats = [];

        foreach (Client::REGISTER_CATEGORIES as $key => $category) {
            if (in_array($category, $newCategories)) {
                array_push($cats, $key);
            }
        }

        sort($cats);

        $clientCategories = explode(',', $clientCategories);

        $removedCategories = array_diff($clientCategories, $newCategories);

        if (count($removedCategories) > 0) {
            foreach ($removedCategories as $index => $value)  {

                $files = ClientAttachment::where(['client_id' => $clientId, 'category_id' => $value])->get();

                if ($files->count() > 0) {
                    foreach ($files as $attachment) {
                        removeFile(ClientAttachment::DIR, $attachment->file);
                        $attachment->delete();
                    }
                }
            }
        }
    }
}
