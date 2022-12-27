<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BaseApiController extends Controller
{
    /**
     * @OA\Info(
     *      version="1.0.0",
     *      title="MO API Documentation",
     *      description="MO Api documentation description",
     *      @OA\Contact(
     *          email="contact@nxb.com.pk"
     *      ),
     *      @OA\License(
     *          name="Apache 2.0",
     *          url="http://www.apache.org/licenses/LICENSE-2.0.html"
     *      )
     * )
     *
     * @OA\Server(
     *      url=L5_SWAGGER_CONST_HOST,
     *      description="API Base URL"
     * )
     *
     * @OA\SecurityScheme(
     *    securityScheme="BearerAppKey",
     *    in="header",
     *    name="Authorization",
     *    type="apiKey"
     * ),
     *
     * @OA\SecurityScheme(
     *    securityScheme="BearerToken",
     *    in="header",
     *    name="Authorization",
     *    type="apiKey"
     * ),
     */


    public function sendResponse($data, $message, $code = HTTP_SUCCESS)
    {
        $response = [
            'statusCode'    => $code,
            'data'          => $data,
            'message'       => $message,
        ];

        return response()->json($response, HTTP_SUCCESS);
    }

    /**
     * return error response.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendError($error, $errorMessages = [], $code = HTTP_NOT_FOUND)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, $code);
    }
}
