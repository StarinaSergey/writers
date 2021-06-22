<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => 'required|max:20',
            'lastname' => 'required|max:20',
            'email' => 'required|email|max:60',
            'password' => 'required|max:255',
            'phone' => 'required|max:20',
            'phone_code' => 'required|max:8',
            'country' => 'required|max:3',
            'timezone' => 'required|max:64'
        ];
    }

    public function messages()
    {
        return [
             'email.email' => 'Email not correct!',
        ];
    }
}
