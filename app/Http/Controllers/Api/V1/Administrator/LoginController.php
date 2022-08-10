<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\Users\Administrator\LoginRequest;

class LoginController extends Controller
{
    /**
     * * @OA\Post (
     *       path="/api/v1/admin/login",
     *       operationId="login",
     *       tags={"Admin"},
     *       summary="Authenticate administrative user",
     *       description="Returns the authorization information for an authenticated administrative user.",
     *       @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="application/json",
     *             @OA\Schema(
     *                 @OA\Property(
     *                 type="object",
     *                 required={"email", "password"},
     *                      @OA\Property(
     *                          description="Administrator E-mail",
     *                          property="email",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          description="Administrator Password",
     *                          property="password",
     *                          type="string"
     *                      )
     *                 ),
     *                 example={
     *                     "email": "admin@buckhill.co.uk",
     *                     "password": "********"
     *                 }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Login was successful."),
     *              @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0Ojk3MDAvYXBpL3YxL2FkbWluL2xvZ2luIiwiaWF0IjoxNjYwMDkxNTk0LCJleHAiOjE2NjAwOTUxOTQsIm5iZiI6MTY2MDA5MTU5NCwianRpIjoiR3hHOVJWWjExV0syaGtJUCIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwidXNlcl91dWlkIjoiZGU4MWZmOWItNTQ4MC00OGEyLTlmNTQtZGE3OTU3ZTQwMTFmIn0.i5ilpi9xXkOi9TdnfcYNRUkxqbpagtqCKbmFVj1Xyg0c"),
     *              @OA\Property(property="type", type="string", example="bearer"),
     *              @OA\Property(property="firstName", type="string", example="Chris"),
     *              @OA\Property(property="lastName", type="string", example="Pine"),
     *              @OA\Property(property="email", type="string", example="admin@buckhill.co.uk")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Token generation failed.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Route Not found"
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Unprocessable Entity"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     * Authenticate adminstrator login credentials.
     *
     * @param  \App\Http\Requests\Users\Administrator\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        // Validated login request
        $credentials = $request->only('email', 'password');

        // Create auth token if user exists and password is valid
        $token = Auth::attempt($credentials);

        if ($token) {
            $user = auth()->user();
            $model = new  User();

            // Update user last login time
            $model->where('uuid', $user['uuid'])->update([
                'last_login_at' => now(),
            ]);

            $response = [
                'data' => [
                    'message'   => "Login was successful.",
                    'token'     => $token,
                    'type'      => 'bearer',
                    'firstName' => $user['first_name'],
                    'lastName'  => $user['last_name'],
                    'email'     => $user['email'],
                ]
            ];
            return response($response, 200);
        } else {
            return response(
                [
                    "message" => 'Token generation failed.',
                    "error" => 'Unauthorized'
                ],
                401
            );
        }
    }

    /**
     * @OA\Post (
     *       path="/api/v1/admin/logout",
     *       operationId="logout",
     *       tags={"Admin"},
     *       summary="Logout administrator",
     *       description="Logs out the current authenticated administrative user.",
     *       security={ {"bearer": {} }},
     *       @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Successfully logged out.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Route Not found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     *
     * Logout currently authenticated user.
     *
     * @return void
     */
    public function logout()
    {
        Auth::logout();

        // Clear cookies from session
        \Cookie::forget('laravel_session');

        return response()->json([
            'message' => 'Successfully logged out.'
        ], 200);
    }

    /**
     * @OA\Get (
     *       path="/api/v1/admin/refresh",
     *       operationId="refresh",
     *       tags={"Admin"},
     *       summary="Refresh token",
     *       description="Returns new token information.",
     *       security={ {"bearer": {} }},
     *       @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="Login was successful."),
     *              @OA\Property(property="token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOi8vbG9jYWxob3N0Ojk3MDAvYXBpL3YxL2FkbWluL2xvZ2luIiwiaWF0IjoxNjYwMDkxNTk0LCJleHAiOjE2NjAwOTUxOTQsIm5iZiI6MTY2MDA5MTU5NCwianRpIjoiR3hHOVJWWjExV0syaGtJUCIsInN1YiI6IjEiLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwidXNlcl91dWlkIjoiZGU4MWZmOWItNTQ4MC00OGEyLTlmNTQtZGE3OTU3ZTQwMTFmIn0.i5ilpi9xXkOi9TdnfcYNRUkxqbpagtqCKbmFVj1Xyg0c"),
     *              @OA\Property(property="type", type="string", example="bearer"),
     *              @OA\Property(property="firstName", type="string", example="Chris"),
     *              @OA\Property(property="lastName", type="string", example="Pine"),
     *              @OA\Property(property="email", type="string", example="admin@buckhill.co.uk")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Route Not found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     *
     * Refresh token for the current authenticated user.
     *
     * @return \Illuminate\Http\Response
     */
    public function refresh()
    {
        $user =  Auth::user();

        $response = [
            'data' => [
                'message'   => "Token was successfully refreshed.",
                'token'     => Auth::refresh(),
                'type'      => 'bearer',
                'firstName' => $user['first_name'],
                'lastName'  => $user['last_name'],
                'email'     => $user['email'],
            ]
        ];
        return response($response, 200);
    }
}
