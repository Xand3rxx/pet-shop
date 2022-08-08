<?php

namespace App\Http\Requests\Users\Administrator;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class CreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        // Execute the validation if the request method is POST
        return [
            'first_name'    => 'bail|required|string',
            'last_name'     => 'bail|required|string',
            'phone_number'  => 'bail|required|string|unique:users',
            'email'         => 'bail|required|string|email|unique:users',
            'password'      => 'bail|required|string|min:5|confirmed',
            'avatar'        => 'bail|required|image|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:256|unique:users,avatar',
            'address'       => 'bail|required|string',
            'is_marketing'  => 'bail|sometimes|boolean|nullable',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return void
     */
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Validation errors',
            'data'      => $validator->errors()
        ], 422));
    }
}
