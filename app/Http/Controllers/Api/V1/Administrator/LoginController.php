<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Users\Administrator\LoginRequest;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
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
        // auth()->user()->token()->revoke();

        return response()->json([
            'message' => 'Successfully logged out'
        ], 200);
    }
}
