<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $fillable = [
        'picture'
    ];

    protected $picture = "/storage/banner/";
    public function getPictureAttribute($upload)
    {
        return  url('/') . $this->picture . $upload;
    }
}
