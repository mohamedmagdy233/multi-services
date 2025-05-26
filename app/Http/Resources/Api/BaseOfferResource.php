<?php

namespace App\Http\Resources\Api;

use App\Http\Resources\MediaResource;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseOfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'sub_service_type' => $this->subServiceType->name,
            'price' => $this->price,
            'lat' => $this->lat,
            'long' => $this->long,
            'location_name' => $this->location_name,
            'country' => $this->country,
            'status' => (int)$this->status,
            'is_fav' => $this->isFavorite(),
            'logo' => getFile(optional($this->media->first())->file), // optional is not important but i added
        ];
    }

    public function isFavorite()
    {
        if (!auth('api')->check()) {
            return false;
        }

        return $this->fav()
            ->where('user_id', auth('api')->id())
            ->where('offer_id', $this->id)
            ->exists();
    }

}
