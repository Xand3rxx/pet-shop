<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class ProductRequest extends FormRequest
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
     * Prepare keys before validation
     */
    public function prepareForValidation()
    {
        $this->merge([
            'price' => (float) \App\Models\Product::removeComma($this->price),
        ]);
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
                'category_uuid' => 'bail|required|string|exists:App\Models\Category,uuid',
                'brand'         => 'sometimes|string|exists:App\Models\Brand,uuid|nullable',
                'title'         => 'bail|required|string',
                'price'         => 'bail|required|numeric',
                'description'   => 'bail|required|string',
                'image'         => 'sometimes|image|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:256|nullable',
            ];
        }

        // Execute the validation if the request method is POST
        return [
            'category_uuid' => 'bail|required|string|exists:App\Models\Category,uuid',
            'brand'         => 'sometimes|string|exists:App\Models\Brand,uuid|nullable',
            'title'         => 'bail|required|string',
            'price'         => 'bail|required|numeric',
            'description'   => 'bail|required|string',
            'image'         => 'sometimes|image|mimes:jpg,jpeg,png,bmp,gif,svg,webp|max:256|nullable',
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
