<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginWithSocialRequest extends FormRequest
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
           'name' => 'required|string|max:255',
            'google_email' => 'nullable|email|max:255',
            'facebook_email' => 'nullable|email|max:255',
            'apple_email' => 'nullable|email|max:255',
            'image' => 'nullable|url',
            'social_type' => 'required|in:google,facebook,apple',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'msg' => "Validation error",
                'errors' => $validator->errors(),
                'status' => 422
            ], 422)
        );
    }


}
