<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogValidation extends FormRequest
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
            'picture' =>  "nullable|image",
            'title' => "required",
            'body' => "required",
            'published_date' => "required",
            'published_time' => "required",
        ];
    }
}
