<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserDetailValidation extends FormRequest
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
            'name' => 'nullable',
            'phone' => 'nullable',
            'email' => 'nullable|email|unique:users,email',
            'address' => 'nullable',
            'avatar' => 'nullable|image',
            'password' => 'required',
        ];
    }
}
