<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeneralOfferRequest extends FormRequest
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
            'title'=>'required',
            'image'=>'required|image',
            'link'=>'required|url',
            'start_date'=>'required|date|after_or_equal:today',
            'end_date'=>'required|date|after_or_equal:start_date',

        ];
    }

    protected function update(): array
    {
        return [
            'title'=>'required',
            'image'=>'nullable|image',
            'link'=>'required|url',
            'start_date'=>'required|date',
            'end_date'=>'required|date|after_or_equal:start_date',

        ];
    }
}
