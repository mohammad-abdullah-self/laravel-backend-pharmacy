<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserDetail extends Model
{
    protected $fillable = [
        'phone', 'avatar', 'address',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $avatar = "/storage/avatar/";
    public function getAvatarAttribute($upload)
    {
        return  url('/') . $this->avatar . $upload;
    }
}
