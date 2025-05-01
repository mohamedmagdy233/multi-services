<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class SettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'privacy' => $this->where('key','privacy')->first()->value,
            'phone' => $this->where('key','phone')->first()->value,
            'email' => $this->where('key','email')->first()->value,

        ];
    }
}
