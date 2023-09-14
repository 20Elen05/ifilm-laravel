<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SignInRequest extends FormRequest
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
            'username' => 'required|min:6|max:12',
            'password' => 'required|min:8|max:16',
        ];
    }

    public function messages()
    {   return[
            'required' => 'Please add a :attribute',
            'min' => ':attribute must have minimum :min symbols',
            'max' => ':attribute must have maximum :max symbols'
        ];
    }
}
