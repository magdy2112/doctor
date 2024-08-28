<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DocterRegisterRequest extends FormRequest
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
            'photo' => 'image|mimes:jpeg,png,jpg|max:2048',
            'phone' => 'required|numeric',
            'address' => 'required|string|max:255',
            'age'=>'required|numeric',
            'experience'=>'required|numeric',
            'qualification'=>['required',rule::in(['Specialist','Consultant ','Professor '])],
            'description'=>'string',
            'city'=>'required|string',
            'gender' => ['required', Rule::in(['male', 'female'])],
            'specialization'=>['required',rule::in(['Cardiologist', 'Dentist', 'Surgeon', 'Radiologist', 'Neurologist', 'Dermatologist', 'ENT Specialist

            ', 'Hematologist', 'Psychiatrist', 'Audiologist'])]

        ];
    }
}







