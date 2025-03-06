<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'title',
        'description',
    ];

    public function author()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
