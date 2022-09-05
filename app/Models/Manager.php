<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory, CreatedModifiedBy;

    protected $guarded = [];

    const STORAGE_DIRECTORY = 'teams/';

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
        return $attribute ? Carbon::parse($attribute)->format(config('settings.createdat_datetime_format')) : '';
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
     *                  Model Relations
     * ======================================================
     */

    /**
     * teamMembers
     *
     * @return mixed
     */
    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'manager_id', 'id');
    }

    /**
     * ======================================================
     *                  Model Scope Queries
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
     *                 Model Helper Functions
     * ======================================================
     */

    /**
     * removeImage
     *
     * @return mixed
     */
    public function removeImage()
    {
        removeFile(self::STORAGE_DIRECTORY, $this->image);
    }
}
