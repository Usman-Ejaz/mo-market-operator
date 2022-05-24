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

    public function scopeFeaturedImage($query)
    {
        return $query->where('featured', '=', 1);
    }

    public function scopeFilterRecords($query)
    {
        $request = request();

        if ($request->has('month')) {
            $query = $query->whereMonth('created_at', '=', $request->get('month'));
        }

        if ($request->has('year')) {
            $query = $query->whereYear('created_at', '=', $request->get('year'));
        }

        if ($request->has('sort')) {
            $query = $query->orderBy('created_at', $request->get('sort'));
        }

        return $query;
    }
}
