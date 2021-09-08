<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ContactFile extends Model
{


    protected $fillable = [
        'contact_id', 'file'
    ];

    // protected $files = "/storage/contact/";
    // public function getFilesAttribute($upload)
    // {
    //     return  url('/') . $this->files . $upload;
    // }

    // public function contact()
    // {
    //     return $this->belongsTo(Contact::class);
    // }
}
