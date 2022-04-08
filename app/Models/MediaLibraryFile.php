<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaLibraryFile extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function mediaLibrary() 
    {
        return $this->belongsTo(MediaLibrary::class, 'media_library_id', 'id');
    }

    public function scopeFeaturedImages($query)
    {
        return $query->where('featured', '=', 1);
    }
}
