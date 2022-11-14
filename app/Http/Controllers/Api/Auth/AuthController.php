<?php

namespace App\Http\Controllers\Api\Auth;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    /**
     *
     * @OA\Tag(
     *     name="Client Authentication",
     *     description="API Endpoints for client login"
     * )
     *
     */


    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api-jwt', ['except' => ['login']]);
    }


    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *      path="/client-auth/login",
     *      operationId="login",
     *      tags={"Client Authentication"},
     *      summary="Exchange auth token for email and password",
     *      description="Returns auth jwt token with lifetime",
     *      
     *      
     *      @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(
     *                     property="username",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="password",
     *                     type="string"
     *                 ),
     *                 example={"username": "MP-LESCO-00001", "password": "randomPassword"}
     *             )
     *         )
     *     ),
     *      
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *                 @OA\Property(
     *                     property="access_token",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="token_type",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="expires_in",
     *                     type="integer"
     *                 ),
     *         )
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
    public function login()
    {
        $credentials = request(['username', 'password']);

        if (!$token = auth('api-jwt')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *      path="/client-auth/me",
     *      operationId="me",
     *      tags={"Client Authentication"},
     *      summary="Get the currently authenticated user",
     *      description="Returns the currently authenticated user",
     *      security={{"BearerAppKey": {}}},
     *      
     *      
     *      @OA\Response(
     *          response=200,
     *          description="Success",
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
    public function me()
    {
        return response()->json(auth('api-jwt')->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * @OA\Post(
     *      path="/client-auth/logout",
     *      operationId="logout",
     *      tags={"Client Authentication"},
     *      summary="Logout the currently authenticated user",
     *      description="Logs out the current user who jwt is in the header",
     *      security={{"BearerAppKey": {}}},
     *      
     *      
     *      @OA\Response(
     *          response=200,
     *          description="Success",
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
    public function logout()
    {
        auth('api-jwt')->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    /**
     * @OA\Post(
     *      path="/client-auth/refresh",
     *      operationId="refresh",
     *      tags={"Client Authentication"},
     *      summary="Refresh the token of current user",
     *      description="Returns new token response with refreshed token",
     *      security={{"BearerAppKey": {}}},
     *      
     *      
     *       @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *                 @OA\Property(
     *                     property="access_token",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="token_type",
     *                     type="string"
     *                 ),
     *                 @OA\Property(
     *                     property="expires_in",
     *                     type="integer"
     *                 ),
     *         )
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
    public function refresh()
    {
        return $this->respondWithToken(auth('api-jwt')->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api-jwt')->factory()->getTTL() * 60,
        ]);
    }
}
