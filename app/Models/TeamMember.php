<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    const STORAGE_DIRECTORY = 'teams/';

    protected $guarded = [];


    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */
    
    /**
     * getCreatedAtAttribute
     *
     * @param  mixed $attribute
     * @return void
     */
    public function getCreatedAtAttribute($attribute)
    {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }
    
    /**
     * getImageAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getImageAttribute($value)
    {
        return $value ? serveFile(self::STORAGE_DIRECTORY, $value) : asset('images/no-image.png');
    }


    /**
     * ======================================================
     *                    Model Relations
     * ======================================================
     */
    
    /**
     * manager
     *
     * @return void
     */
    public function manager()
    {
        return $this->belongsTo(Manager::class, 'manager_id', 'id');
    }


    /**
     * ======================================================
     *               Model Scope Query Functions
     * ======================================================
     */
    
    /**
     * scopeSortByOrder
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeSortByOrder($query)
    {
        return $query->orderBy('order', 'asc');
    }

     /**
     * ======================================================
     *               Model Helper Functions
     * ======================================================
     */
    
    /**
     * removeImage
     *
     * @return void
     */
    public function removeImage()
    {
        if ($this->image !== null && $this->image !== "")
        {
            removeFile(self::STORAGE_DIRECTORY, $this->image);
        }
    }
}
