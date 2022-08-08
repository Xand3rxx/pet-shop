<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use DateTimeImmutable;
use Lcobucci\JWT\Builder;
use Illuminate\Http\Request;
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Lcobucci\JWT\Signer\Key\InMemory;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Users\Administrator\LoginRequest;

class LoginController extends Controller
{
    /**
     * Authenticate adminstrator login credentials.
     *
     * @param  \App\Http\Requests\Users\Administrator\LoginRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        // Return errors if validation fails

        $validator = Validator::make($request->all(), [
            'email'     => 'required|string',
            'password'  => 'required|string',
        ]);

        if ($validator->fails()) {
            return response(['errors' => $request->errors()->all()], 422);
        }

        $validated = $validator->safe()->only(['email', 'password']);

        // Verify if user exists
        $user = \App\Models\User::where('email', $validated['email'])->first();

        // Create auth token if user exists and password is valid
        if ($user) {
            if (Hash::check($validated['password'], $user['password'])) {

                $privateKey = InMemory::base64Encoded(
                    'hiG8DlOKvtih6AxlZn5XKImZ06yu8I3mkOzaJrEuW8yAv8Jnkw330uMt8AEqQ5LB'
                );

                $publicKey = InMemory::base64Encoded(
                    bcrypt('public-key')
                );

                $token = (new JwtFacade())->issue(
                    new Sha256(),
                    bcrypt('create-token'),
                    static fn (
                        Builder $builder,
                        DateTimeImmutable $issuedAt
                    ): Builder => $builder
                        ->issuedBy($validated['email'])
                        ->permittedFor($validated['email'])
                        ->expiresAt($issuedAt->modify('+60 minutes'))
                );

                var_dump($token->claims()->all());
                echo $token->toString();

                // $token = $user->createToken('Hamper Shop Personal Access Client')->accessToken;
                $response = [
                    'data' => [
                        'message'   => "Login was successful.",
                        'token'     => $token,
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
}