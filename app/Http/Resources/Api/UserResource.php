<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'notification_count' => $this->notifications->where('is_seen', 0)->count(),
            'email' => $this->email ?? $this->google_email ?? $this->facebook_email ?? $this->apple_email,
            'user_type' => $this->user_type,
            'status' => (int)$this->status,
            'phone' => $this->phone,
            'is_social' => $this->social_type ? 1 : 0,
            'image' => $this->image ? asset($this->image) : null,

            'jwt_token' => $this->token,
        ];
    }
}
