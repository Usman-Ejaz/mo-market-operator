<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderImage extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STORAGE_DIRECTORY = 'slider_images/';


        
    /**
     * getImageAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getImageAttribute($value)
    {
        return !empty($value) ? serveFile(self::STORAGE_DIRECTORY, $value) : null;
    }

    public function scopeOrderByImageOrder($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
