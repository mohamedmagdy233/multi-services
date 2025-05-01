<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SettingRequest extends FormRequest
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
        if (request()->isMethod('put')) {
            return $this->update();
        } else {
            return $this->store();
        }
    }

    protected function store(): array
    {
        return [
            'email' => 'required|email',
            'phone' => 'required',
            'privacy' => 'required',
            'logo' => 'image',
            'favicon' => 'image',
        ];
    }

    protected function update(): array
    {
        return [
            'logo' => 'image',
            'favicon' => 'image',
            'email' => 'required|email',
            'phone' => 'required',
            'privacy' => 'required',
        ];
    }


}
