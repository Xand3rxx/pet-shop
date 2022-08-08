<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Actions\File;
use Illuminate\Support\Facades\Hash;

class CreateAdmin
{
    use File;

    /**
     * Create a new adminstrative user record in storage.
     *
     * @param array  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function handle(array $request)
    {
        // Set `hasBeenCreated` to false before DB transaction
        (bool) $hasBeenCreated = false;

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, &$hasBeenCreated) {
            $user = User::create([
                'first_name'        => $request['first_name'],
                'last_name'         => $request['last_name'],
                'is_admin'          => User::ROLE['Admin'],
                'email'             => $request['email'],
                'email_verified_at' => now(),
                'password'          => Hash::make($request['password']),
                'phone_number'      => $request['phone_number'],
                'address'           => $request['address'],
                'is_marketing'      => $request['is_marketing'],
            ]);

            if (request()->file('avatar')) {
                $user->update([
                    'avatar'    => $this->createFileRecord(request(), 'avatar')['uuid']
                ]);
            }

            $hasBeenCreated = true;
        }, 3); // Try 3 times before reporting an error

        return $hasBeenCreated;
    }
}
