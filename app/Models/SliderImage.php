<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SliderImage extends Model
{
    use HasFactory, CreatedModifiedBy;

    protected $guarded = [];

    const STORAGE_DIRECTORY = 'slider_images/';

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */
        
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

    /**
     * ======================================================
     *                  Model Scope Queries
     * ======================================================
     */
    
    /**
     * scopeOrderByImageOrder
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeOrderByImageOrder($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
