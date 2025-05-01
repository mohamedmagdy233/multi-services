<?php

namespace App\Http\Requests\Api;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FilteredOffersRequest extends FormRequest
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
            'min_price' => 'required|numeric',
            'max_price' => 'required|numeric|gte:min_price',
            'sub_service_type_id' => 'required|exists:sub_service_types,id',
            'service_type_id' => 'required|exists:service_types,id',
            'lat' => 'required|numeric',
            'long' => 'required|numeric',
            'type' => 'required|in:asc,desc',
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
