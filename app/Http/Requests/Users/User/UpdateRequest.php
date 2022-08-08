<?php

namespace App\Http\Requests\Users\User;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UpdateRequest extends FormRequest
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
        // Execute the validation if the request method is PUT
        if ($this->isMethod('PUT')) {
            return [
                'first_name'    => 'bail|required|string',
                'last_name'     => 'bail|required|string',
                'email'         => ['bail', 'required', 'string', 'email', Rule::unique('users', 'email')->ignore($this->route('user')['email'], 'email')],
                'password'      => 'bail|required|string|min:5|confirmed',
                'phone_number'  => ['bail', 'required', 'string', Rule::unique('users', 'phone_number')->ignore($this->route('user')['phone_number'], 'phone_number')],
                'avatar'        => 'sometimes|image|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:256|nullable|unique:users,avatar',
                'address'       => 'bail|required|string',
                'is_marketing'  => 'bail|sometimes|boolean',
            ];
        }

        // Execute the validation if the request method is POST
        return [
            'first_name'    => 'bail|required|string',
            'last_name'     => 'bail|required|string',
            'phone_number'  => 'bail|required|string|unique:users',
            'email'         => 'bail|required|string|email|unique:users',
            'password'      => 'bail|required|string|min:5|confirmed',
            'avatar'        => 'sometimes|image|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:256|nullable|unique:users,avatar',
            'address'       => 'bail|required|string',
            'is_marketing'  => 'bail|required|boolean',
        ];
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
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
