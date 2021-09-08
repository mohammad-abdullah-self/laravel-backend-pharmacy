<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserFeedback extends Model
{
    protected $fillable = [
        'user_id', 'stars', 'comment', 'published'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('published', 1);
    }
}
