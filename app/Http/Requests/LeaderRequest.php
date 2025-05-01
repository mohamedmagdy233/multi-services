<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaderRequest extends FormRequest
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
            'phone' => 'required|string|max:255|unique:users,phone',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'required|string|min:5|confirmed',
            'image' => 'required|image',

        ];
    }

    protected function update(): array
    {
        return [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255|unique:users,phone,' . $this->id,
            'email' => 'nullable|email|unique:users,email,' . $this->id,
            'password' => 'nullable|string|min:5|confirmed',
            'image' => 'nullable|image',

        ];
    }
}
