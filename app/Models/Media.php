<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends BaseModel
{
    use HasFactory;

    protected $table = 'media';



    public function model()
    {
        return $this->morphTo();
    }


}
