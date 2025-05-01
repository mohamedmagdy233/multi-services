<?php

namespace App\Models;

class Offer extends BaseModel
{
    protected $casts = [];


    public function media()
    {
        return $this->morphMany(Media::class, 'model');

    }

    public function user()
    {

        return $this->belongsTo(User::class);
    }

    public function fav()
    {

        return $this->hasMany(Fav::class);

    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);

    }

    public function subServiceType()
    {
        return $this->belongsTo(SubServiceType::class);

    }

}
