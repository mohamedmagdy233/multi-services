<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
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
            'message' => $this->type == 1 ? asset($this->message) : $this->message,
            'type' => (int)$this->type,
            'room_id' => $this->room_id,
            'is_me'=> (int)($this->sender_id == auth()->user()->id),
            'is_seen' => (int)$this->is_seen,
            'created_at' => $this->created_at->format('d M Y'),

        ];
    }



}
