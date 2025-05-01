<?php

namespace App\Models;


use App\Traits\AutoFillable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject


{
    use AutoFillable;
    protected $guarded =[];
    protected $casts = [];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }


    public function getJWTCustomClaims()
    {
        return [];
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);

    }

    public function deviceTokens()
    {
        return $this->hasMany(DeviceToken::class);

    }

    public function offers()
    {
        return $this->hasMany(Offer::class);

    }

    public function favs()
    {
        return $this->hasMany(Fav::class);

    }



}
