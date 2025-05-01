<?php

namespace App\Http\Resources\Api;

use App\Models\GeneralOffer;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class NotificationResourece extends JsonResource
{

    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'title' => $this->title,
            'is_seen' => $this->is_seen,
            'body' => $this->body,
            'reference_id' => $this->reference_id,
            'reference_table' =>$this->reference_table,
            'reference_link'=>$this->reference_table=='general_offers' ? GeneralOffer::find($this->reference_id)->link :null, // mobile developer who order this key
            'created_at' => Carbon::parse($this->created_at)->isoFormat('Y MMMM D'),
        ];
    }
}
