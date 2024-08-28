<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [

            'name' =>'required|string|max:255',
            'email' =>'required|string|email|max:255',
            'password' => 'required|min:3|confirmed',
            'city'=>'required|string',
            'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'phone' => 'numeric',
            'address' => 'string|max:255',
            'age'=>'numeric',
            'gender' => [ Rule::in(['male', 'female'])],

        ];
    }
}


   