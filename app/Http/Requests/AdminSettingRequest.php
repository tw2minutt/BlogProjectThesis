<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminSettingRequest extends FormRequest
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
            'name' =>'required',
            'email' =>'required|email:rfc,dns',
            'image' =>'required|mimes:jpg,bmp,png',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => ':attributes field is required.',
            'email.required' => ':attributes field is required.',
            'email.email' => ':attributes field is wrong format',
            'image.required' => ':attributes field is required.',
            'image.mimes' => ':attributes field must an image of types jpg,png,...',
        ];
    }
    public function attributes()
    {
        return [
            'name' => 'Name',
            'email' => 'Email',
            'image' => 'Image',
        ];
    }
}
