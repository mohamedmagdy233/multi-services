<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\MediaResource;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class GeneralOfferResource extends JsonResource
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
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'image' => getFile($this->image),
            'link' => $this->link,
            'start_date' => Carbon::parse($this->start_date)->format('d F Y'),
            'end_date' => Carbon::parse($this->end_date)->format('d F Y'),

        ];
    }
}
