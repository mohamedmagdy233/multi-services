<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SubServiceTypeRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        if ($this->isMethod('put')) {
            return $this->update();
        } else {
            return $this->store();
        }
    }

    protected function store(): array
    {
        return [
            'name' => 'required|string|max:255',
            'service_type_id' => 'required|exists:service_types,id',

        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'required|string|max:255',
            'service_type_id' => 'required|exists:service_types,id',

        ];
    }
}
