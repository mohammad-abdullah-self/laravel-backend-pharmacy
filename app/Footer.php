<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Footer extends Model
{
    protected $fillable = [
        'logo', 'name', 'description', 'f_link', 't_link', 'y_link', 'phone', 'houre', 'email', 'address'
    ];

    protected $logo = "/storage/logo/";
    public function getLogoAttribute($upload)
    {
        return  url('/') . $this->logo . $upload;
    }
}
