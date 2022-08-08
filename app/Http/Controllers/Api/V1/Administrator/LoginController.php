<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Traits\JWTTokenGenerator;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Users\Administrator\LoginRequest;


class LoginController extends Controller
{
    use JWTTokenGenerator;

    /**
     * Authenticate adminstrator login credentials.
     *
     * @param  \App\Http\Requests\Users\Administrator\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(LoginRequest $request)
    {
        // Verify if user exists
        $user = \App\Models\User::where('email', $request['email'])->first();

        // Create auth token if user exists and password is valid
        if ($user) {
            if (Hash::check($request['password'], $user['password'])) {
                $data = $this->generateToken($user);
                $response = [
                    'data' => [
                        'message'   => "Login was successful.",
                        'token'     => $data['token'],
                        'expires'   => $data['expires'],
                        'firstName' => $user['first_name'],
                        'lastName'  => $user['last_name'],
                        'email'     => $user['email'],
                    ]
                ];
                return response($response, 200);
            } else {
                return response(
                    ["error" => "These credentials do not match our records."],
                    401
                );
            }
        } else {
            return response(
                ["error" => 'User does not exist.'],
                404
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
