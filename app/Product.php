<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'category_id', 'name', 'generic', 'type', 'manufactured', 'picture', 'size', 'quantity', 'pieces_per_pata', 'dose', 'old_mrp', 'mrp'
    ];

    protected $picture = "/storage/product/";
    public function getPictureAttribute($upload)
    {
        return  url('/') . $this->picture . $upload;
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
