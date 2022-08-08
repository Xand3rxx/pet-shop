<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Users\Administrator\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
     /**
     * Allow access to login route.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }

    /**
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
     * Logout currently authenticated user.
     *
     * @return void
     */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }

    /**
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
