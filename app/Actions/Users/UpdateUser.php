<?php

namespace App\Actions\Users;

use App\Models\User;
use App\Actions\File;
use Illuminate\Support\Facades\Hash;

class UpdateUser
{
    use File;

    /**
     * Create a new adminstrative user record in storage.
     *
     * @param object  $user
     * @param array  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function handle(object $user, array $request)
    {
        // Set `hasBeenUpdated` to false before DB transaction
        (bool) $hasBeenUpdated = false;

        \Illuminate\Support\Facades\DB::transaction(function () use ($user, $request, &$hasBeenUpdated) {
            $updateUser = $user->update([
                'first_name'        => $request['first_name'],
                'last_name'         => $request['last_name'],
                'is_admin'          => User::ROLE['User'],
                'email'             => $request['email'],
                'password'          => Hash::make($request['password']),
                'phone_number'      => $request['phone_number'],
                'address'           => $request['address'],
                'is_marketing'      => $request['is_marketing'],
            ]);

            if (request()->file('avatar')) {
                $updateUser->update([
                    'avatar'    => $this->createFileRecord(request())['uuid']
                ]);
            }

            $hasBeenUpdated = true;
        }, 3); // Try 3 times before reporting an error

        return $hasBeenUpdated;
    }
}
