<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory, CreatedModifiedBy;

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
     * @return mixed
     */
    public function getCreatedAtAttribute($attribute)
    {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }
    
    /**
     * getImageAttribute
     *
     * @param  mixed $value
     * @return mixed
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
     * @return mixed
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
     * @return mixed
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
     * @return mixed
     */
    public function removeImage()
    {
        if ($this->image !== null && $this->image !== "")
        {
            removeFile(self::STORAGE_DIRECTORY, $this->image);
        }
    }
}
