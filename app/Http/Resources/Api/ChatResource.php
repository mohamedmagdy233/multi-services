<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class ChatResource extends JsonResource
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
            'room_id' => $this->id,
            'receiver_id' => $this->receiver_id !== auth('api')->user()->id ? $this->receiver_id : $this->sender_id,
            'receiver_name' => $this->receiver_id !== auth('api')->user()->id ? $this->receiver->name : $this->sender->name,
            'receiver_image' => $this->receiver_id !== auth('api')->user()->id ?
                ($this->receiver->image ? asset($this->receiver->image) : getFileWithName($this->receiver->name))
                : ($this->sender->image ? asset($this->sender->image) : getFileWithName($this->sender->name)),

            'last_message' => $this->whenLoaded('chats', function () {
                $lastChat = $this->chats()->latest()->first();
                if ($lastChat) {
                    return $lastChat->type == 1 ? asset($lastChat->message) : $lastChat->message;
                }
                return null;
            }),

            'type' => $this->whenLoaded('chats', function () {
                $lastChat = $this->chats()->latest()->first();
                return $lastChat ? ($lastChat->type == 1 ? 1 : 0) : null;
            }),

            'created_at' => $this->whenLoaded('chats', function () {
                $lastChat = $this->chats()->latest()->first();
                return $lastChat ? $lastChat->created_at->format('d M Y') : null;
            }),
            'is_me' => (int)($this->sender_id == auth()->user()->id),
            'is_seen' => (int)$this->is_seen,


        ];
    }
}
