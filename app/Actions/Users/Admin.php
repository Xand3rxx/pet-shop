<?php

namespace App\Actions\Users;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

class Admin
{
    /**
     * Create a new adminstrative user record in storage.
     *
     * @param array  $request
     * @return \Illuminate\Http\Response
     */
    public function handle(array $request)
    {
        return User::create([
            'first_name'        => $request['first_name'],
            'last_name'         => $request['last_name'],
            'is_admin'          => User::ROLE['Admin'],
            'email'             => $request['email'],
            'email_verified_at' => now(),
            'password'          => Hash::make($request['password']),
            'phone_number'      => $request['phone_number'],
            'address'           => $request['address'],
            'is_marketing'      => User::MARKETER_ROLE['Marketer'],
        ]);
    }
}
