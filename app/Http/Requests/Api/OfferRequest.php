<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OfferRequest extends FormRequest
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
            'service_type_id' => 'required|exists:service_types,id',
            'sub_service_type_id' => 'required|exists:sub_service_types,id',
            'price' => 'nullable|numeric',
            'title' => 'required|string',
            'body' => 'required|string',
            'is_phone_hide' => 'required|boolean',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'media' => 'required|array',
            'location_name' => 'required|string',
            'country' => 'required|string',
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
