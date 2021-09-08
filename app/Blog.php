<?php

namespace App;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Blog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title', 'body', 'published_at', 'picture', 'slug'
    ];

    protected $picture = "/storage/blog/";
    public function getPictureAttribute($upload)
    {
        return  url('/') . $this->picture . $upload;
    }

    public function getPublishedAtAttribute($dateTime)
    {
        $date = new DateTime($dateTime);

        return  $date->format('yy-m-d h:i a ');
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<=', now());
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}
