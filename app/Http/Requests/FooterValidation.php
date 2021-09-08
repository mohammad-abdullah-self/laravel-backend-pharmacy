<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FooterValidation extends FormRequest
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
            'logo' => 'nullable|image',
            'name' => 'required',
            'description' => 'required',
            'f_link' => 'required',
            't_link' => 'required',
            'y_link' => 'required',
            'phone' => 'required',
            'houre' => 'required',
            'email' => 'required|email',
            'address' => 'required',
        ];
    }
}
