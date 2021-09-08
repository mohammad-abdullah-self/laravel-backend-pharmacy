<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'email', 'phone', 'subject', 'comment'
    ];

    // public function contactFiles()
    // {
    //     return $this->hasMany(ContactFile::class);
    // }
}
