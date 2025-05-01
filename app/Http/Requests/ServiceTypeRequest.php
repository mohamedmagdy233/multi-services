<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceTypeRequest extends FormRequest
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
            'name'=>'required',
            'image'=>'required|image',
            'need_price'=>'required|in:0,1',

        ];
    }

    protected function update(): array
    {
        return [
            'name'=>'required',
            'image'=>'nullable|image',
            'need_price'=>'required|in:0,1',

        ];
    }
}
