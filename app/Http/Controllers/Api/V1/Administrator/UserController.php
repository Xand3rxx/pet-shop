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
     * * @OA\Get (
     *       path="/api/v1/admin/user-listing",
     *       operationId="listUsers",
     *       tags={"Admin"},
     *       summary="Display all non-administrative users",
     *       description="Returns a list of all non-administrative usersinformation.",
     *       security={ {"bearer": {} }},
     *       @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *            @OA\Property(property="message", type="string", example="1 user record(s) found."),
     *            @OA\Property(
     *              type="array",
     *              property="users",
     *              @OA\Items(
     *              type="object",
     *              @OA\Property(property="id", type="number", example=2),
     *              @OA\Property(property="uuid", type="string", example="754f68bc-b724-4a4c-91da-970cc5592f8e"),
     *              @OA\Property(property="first_name", type="string", example="Carolyn"),
     *              @OA\Property(property="last_name", type="string", example="Mann"),
     *              @OA\Property(property="is_admin", type="number", example=0),
     *              @OA\Property(property="email", type="string", example="john.doe@gmail.com"),
     *              @OA\Property(property="email_verified_at", type="string", example="2022-08-09T22:50:05.000000Z"),
     *              @OA\Property(property="avatar", type="string", example="null"),
     *              @OA\Property(property="phone_number", type="string", example="1-831-953-3966"),
     *              @OA\Property(property="address", type="string", example="75975 Cleora Meadows\nWest Davebury, CT 86322"),
     *              @OA\Property(property="is_marketing", type="number", example=0),
     *              @OA\Property(property="updated_at", type="string", example="2022-08-09T22:50:05.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2022-08-09T22:50:05.000000Z")
     *          ),
     *         ),
     *        )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="No products records found"
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Internal Server Error"
     *      )
     * )
     *
     * Display a listing of all non Administrator users.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::basicUsers()->latest()->limit(100)->paginate(20);
        return (count($users))
            ? response()->json(
                [
                    'data' =>    [
                        'message' => count($users) . ' user record(s) found.',
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

     *
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post (
     *       path="/api/v1/admin/create",
     *       operationId="storeAdministratorUser",
     *       tags={"Admin"},
     *       summary="Store new administrator user record",
     *       description="Returns the created administrator information",
     *       security={ {"bearer": {} }},
     *       @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"first_name", "last_name", "email", "password", "password_confirmation", "phone_number", "address", "is_marketing"},
     *                      @OA\Property(
     *                          description="First Name",
     *                          property="first_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          description="Last Name",
     *                          property="last_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          description="E-mail Address",
     *                          property="email",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          description="Password",
     *                          property="password",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          description="Confirm Password",
     *                          property="password_confirmation",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          description="Avatar",
     *                          property="avatar",
     *                          type="file",
     *                      ),
     *                      @OA\Property(
     *                          description="Phone Number",
     *                          property="phone_number",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          description="Address",
     *                          property="address",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          description="Marketing",
     *                          property="is_marketing",
     *                          type="number",
     *                      ),
     *                 example={
     *                     "first_name": "Felix",
     *                     "last_name": "James",
     *                     "email": "felix.james@gmail.com",
     *                     "email_verified_at": "2022-08-09T22:50:05.000000Z",
     *                     "avatar": "null",
     *                     "phone_number": "1-831-953-3966",
     *                     "address": "75975 Cleora Meadows\\nWest Davebury, CT 86322",
     *                     "is_marketing": 0,
     *                     "updated_at": "2022-08-09T22:50:05.000000Z",
     *                     "created_at": "2022-08-09T22:50:05.000000Z"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Administrator user was successfully created."),
     *              @OA\Property(property="first_name", type="string", example="Felix"),
     *              @OA\Property(property="last_name", type="string", example="James"),
     *              @OA\Property(property="is_admin", type="number", example=0),
     *              @OA\Property(property="email", type="string", example="felix.james@gmail.com"),
     *              @OA\Property(property="email_verified_at", type="string", example="2022-08-09T22:50:05.000000Z"),
     *              @OA\Property(property="avatar", type="string", example="null"),
     *              @OA\Property(property="phone_number", type="string", example="1-831-953-3966"),
     *              @OA\Property(property="address", type="string", example="75975 Cleora Meadows\\nWest Davebury, CT 86322"),
     *              @OA\Property(property="is_marketing", type="number", example=0),
     *              @OA\Property(property="uuid", type="string", example="0f2de771-25dc-488d-81f6-95ba99bfebc8"),
     *              @OA\Property(property="updated_at", type="string", example="2022-08-09T10:25:52.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2022-08-09T10:25:52.000000Z"),
     *              @OA\Property(property="last_login_at", type="string", example="null"),
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="file", type="string", example="null")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="An error occurred while trying to create an administrator user."),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="Route Not Found"
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
     *
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
            ], 400);
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
     * @OA\Put (
     *       path="/api/v1/admin/create",
     *       operationId="updateUser",
     *       tags={"Admin"},
     *       summary="Update a user record",
     *       description="Update user information with existing UUID.",
     *       security={ {"bearer": {} }},
     *       @OA\Parameter(
     *           in="path",
     *           description="User UUID",
     *           name="uuid",
     *           @OA\Schema(type="string"),
     *           required=true
     *      ),
     *       @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 type="object",
     *                 required={"first_name", "last_name", "email", "password", "password_confirmation", "phone_number", "address", "is_marketing"},
     *                      @OA\Property(
     *                          description="First Name",
     *                          property="first_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          description="Last Name",
     *                          property="last_name",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          description="E-mail Address",
     *                          property="email",
     *                          type="number"
     *                      ),
     *                      @OA\Property(
     *                          description="Password",
     *                          property="password",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          description="Confirm Password",
     *                          property="password_confirmation",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          description="Avatar",
     *                          property="avatar",
     *                          type="file",
     *                      ),
     *                      @OA\Property(
     *                          description="Phone Number",
     *                          property="phone_number",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          description="Address",
     *                          property="address",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          description="Marketing",
     *                          property="is_marketing",
     *                          type="number",
     *                      ),
     *                 example={
     *                     "first_name": "Felix",
     *                     "last_name": "James",
     *                     "email": "felix.james@gmail.com",
     *                     "email_verified_at": "2022-08-09T22:50:05.000000Z",
     *                     "avatar": "null",
     *                     "phone_number": "1-831-953-3966",
     *                     "address": "75975 Cleora Meadows\\nWest Davebury, CT 86322",
     *                     "is_marketing": 0,
     *                     "updated_at": "2022-08-09T22:50:05.000000Z",
     *                     "created_at": "2022-08-09T22:50:05.000000Z"
     *                }
     *             )
     *         )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              type="object",
     *              @OA\Property(property="message", type="string", example="Administrator user was successfully created."),
     *              @OA\Property(property="first_name", type="string", example="Felix"),
     *              @OA\Property(property="last_name", type="string", example="James"),
     *              @OA\Property(property="is_admin", type="number", example=0),
     *              @OA\Property(property="email", type="string", example="felix.james@gmail.com"),
     *              @OA\Property(property="email_verified_at", type="string", example="2022-08-09T22:50:05.000000Z"),
     *              @OA\Property(property="avatar", type="string", example="null"),
     *              @OA\Property(property="phone_number", type="string", example="1-831-953-3966"),
     *              @OA\Property(property="address", type="string", example="75975 Cleora Meadows\\nWest Davebury, CT 86322"),
     *              @OA\Property(property="is_marketing", type="number", example=0),
     *              @OA\Property(property="uuid", type="string", example="0f2de771-25dc-488d-81f6-95ba99bfebc8"),
     *              @OA\Property(property="updated_at", type="string", example="2022-08-09T10:25:52.000000Z"),
     *              @OA\Property(property="created_at", type="string", example="2022-08-09T10:25:52.000000Z"),
     *              @OA\Property(property="last_login_at", type="string", example="null"),
     *              @OA\Property(property="id", type="number", example=1),
     *              @OA\Property(property="file", type="string", example="null")
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="An error occurred while trying to create an administrator user."),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="An Administrator user cannot be updated.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User Not Found"
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
     *
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
            ], 400);
    }

    /**
     * * @OA\Delete (
     *       path="/api/v1/admin/user-delete/{uuid}",
     *       operationId="deleteUser",
     *       tags={"Admin"},
     *       summary="Delete user record",
     *       description="Delete user information with existing UUID.",
     *       security={ {"bearer": {} }},
     *       @OA\Parameter(
     *           in="path",
     *           description="User UUID",
     *           name="uuid",
     *           @OA\Schema(type="string"),
     *           required=true
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="success",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="User was successfully deleted."),
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Bad Request",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="An error occurred while trying to delete user."),
     *          )
     *      ),
     *      @OA\Response(
     *          response=401,
     *          description="Unauthenticated"
     *      ),
     *      @OA\Response(
     *          response=403,
     *          description="Forbidden",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="An Administrator user cannot be deleted.")
     *          )
     *      ),
     *      @OA\Response(
     *          response=404,
     *          description="User Not Found"
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
     *
     * Remove the specified resource from storage.
     *
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
            ], 403);
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
            ], 400);
    }
}
