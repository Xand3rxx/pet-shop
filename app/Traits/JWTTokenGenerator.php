<?php

namespace App\Traits;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

trait JWTTokenGenerator
{
    /**
     * Generate JWT token using PHP firebase JWT
     *
     * @param object  $user
     * @return mixed  $token
     */
    public function generateToken(object $user)
    {
        $expirationTime =  time() + 60000; // 60 minutes
        $payload = [
            'iss'   => 'localhost',
            'aud'   => 'localhost',
            'iat'   => time(),
            'exp'   => $expirationTime,
            'uuid'  => $user['uuid'],
            'email' => $user['email'],
        ];

        return [
            'token' => JWT::encode($payload, $this->key(), 'HS256'),
            'expires' => $expirationTime,
        ];
    }

    public function decodeToken($token)
    {
        dd(request()->user());
        try {
            $token = str_replace('"', "", $token);
            dd($token);

            $decoded = JWT::decode($token, new Key($this->key(), 'HS256'));

            return (isset($decoded->uuid) && $decoded->uuid == auth()->user()->uuid) ? true : false;
        } catch(\Firebase\JWT\ExpiredException $e){
            return response([
                'error' => 'Caught exception: ',  $e->getMessage(),
            ], 422);
       }
    }

    private function key()
    {
        return bcrypt('json-web-token');
    }
}
