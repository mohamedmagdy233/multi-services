<?php

namespace App\Http\Resources\Api;

use App\Models\Room;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $room=Room::where('sender_id',auth('api')->id())
            ->where('receiver_id',$this->user_id)
            ->orWhere('receiver_id',auth('api')->id())
            ->where('sender_id',$this->user_id)
            ->first();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'body' => $this->body,
            'price' => $this->price,
            'is_phone_hide' => $this->is_phone_hide,
            'lat' => $this->lat,
            'long' => $this->long,
            'location_name' => $this->location_name,
            'is_fav' => $this->isFavorite(),
            'is_open' => $this->is_open,
            'status' => $this->status,
            'media' => MediaResource::collection($this->media),
            'is_mine' => auth('api')->check() && $this->user_id == auth('api')->id(),


            'provider'=>[
                'id'=>$this->user->id,
                'name'=>$this->user->name,
                'image'=>getFile($this->user->image),
                'posted_at'=>$this->created_at->diffForHumans(),
                'phone'=>$this->user->phone,
                'email'=>$this->user->email??null,
                'room_id'=>$room?$room->id:null,

            ]
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
