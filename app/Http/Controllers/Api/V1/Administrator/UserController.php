<?php

namespace App\Http\Controllers\Api\V1\Administrator;

use App\Models\User;
use App\Traits\AdminAccess;
use Illuminate\Http\Request;
use App\Actions\Users\UpdateUser;
use App\Actions\Users\CreateAdmin;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\User\UpdateRequest;
use App\Http\Requests\Users\Administrator\CreateRequest;

class UserController extends Controller
{
    use AdminAccess;
    /**
     * Display a listing of all non Administrator users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::basicUsers()->limit(100)->paginate(20);
        return (count($users))
            ? response()->json(
                [
                    'data' =>    [
                        'message' => count($users) . ' user records found.',
                        'users' => $users->toArray(),
                    ]
                ],
                200
            )
            : response()->json([
                'data' => [
                    'message' => 'Sorry! No user records found',
                    'users' => [],
                ]
            ], 404);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Users\Administrator\CreateRequest  $request
     * @param  \App\Actions\Users\CreateAdmin  $createAdmin
     * @return \Illuminate\Http\Response
     */
    public function store(CreateRequest $request, CreateAdmin $createAdmin)
    {
        return ($createAdmin->handle($request->validated()))
            ? response()->json(
                [
                    'data' =>    [
                        'message' => 'Administrator user was successfully created.',
                    ]
                ],
                200
            )
            : response()->json([
                'data' => [
                    'message' => 'An error occurred while trying to create an administrator user.',
                ]
            ], 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Users\User\UpdateRequest  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user, UpdateUser $updateUser)
    {
        // Prevent Administrator account from being deleted
        if ($this->preventFromDBeingDeletedOrEdited($user['is_admin'])) {
            return response()->json([
                'data' => [
                    'message' => 'An Administrator user cannot be edited.',
                ]
            ], 400);
        }

        return ($updateUser->handle($user, $request->validated()))
            ? response()->json(
                [
                    'data' =>    [
                        'message' => 'User account was successfully updated.',
                    ]
                ],
                200
            )
            : response()->json([
                'data' => [
                    'message' => 'An error occurred while trying to update user account.',
                ]
            ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @param  \App\Models\User  $user
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        // Prevent Administrator account from being deleted
        if ($this->preventFromDBeingDeletedOrEdited($user['is_admin'])) {
            return response()->json([
                'data' => [
                    'message' => 'An Administrator user cannot be deleted.',
                ]
            ], 400);
        }

        return ($user->delete())
            ? response()->json(
                [
                    'data' =>    [
                        'message' => 'User was successfully deleted.',
                    ]
                ],
                200
            )
            : response()->json([
                'data' => [
                    'message' => 'An error occurred while trying to delete user.',
                ]
            ], 500);
    }
}
