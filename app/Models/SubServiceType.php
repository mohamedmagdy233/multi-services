<?php

namespace App\Models;

class SubServiceType extends BaseModel
{
    protected $casts = [];


    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);

    }

}
