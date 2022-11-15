<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePassword extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'current_password' => 'required',
            'password' => 'required',
            'repeat_password' => 'required|same:password',
        ];
    }

    public function messages()
    {
        return [
            'current_password.required' => 'Current password is required!',
            'password.required' => 'Password is required!',
            'repeat_password.same' => 'Password is different!'
        ];
    }
}
