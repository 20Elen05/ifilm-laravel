<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignUpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'firstName' => 'required|min:3|max:10|alpha',
            'surname' => 'required|min:3|max:16|alpha',
            'username' => 'required|min:6|max:12|unique:users',
            'email' => 'required',
            'password' => 'required|min:8|max:16',
        ];
    }

    public function messages()
    {
        return [
            'required' => ':Attribute is required ',
            'alpha' => 'The :attributes must contain only letters',
            'min' => ':Attribute must have at least :min symbols',
            'max' => ':Attribute must have maximum :max symbols',
            'email' => 'This :attribute is already in use',
            'unique' => ':This :attribute is already in use'
        ];
    }
}
