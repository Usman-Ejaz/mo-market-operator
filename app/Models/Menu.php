<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory, CreatedModifiedBy;

    protected $guarded = [];

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */

    /**
     * getActiveAttribute
     *
     * @param  mixed $attribute
     * @return mixed
     */
    public function getActiveAttribute($attribute)
    {
        return (isset($attribute)) ? $this->activeOptions()[$attribute] : '';
    }

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
     * ======================================================
     *                  Model Scope Queries
     * ======================================================
     */

    /**
     * scopeByTheme
     *
     * @param  mixed $query
     * @param  mixed $theme
     * @return mixed
     */
    public function scopeByTheme($query, $theme = null)
    {
        if ($theme === null) {
            $theme = Settings::get_option('current_theme');
        }
        return $query->where('theme', $theme);
    }

    /**
     * scopeActive
     *
     * @param  mixed $query
     * @return mixed
     */
    public function scopeActive($query)
    {
        return $query->where('active', '=', 1);
    }

    /**
     * ======================================================
     *                 Model Helper Functions
     * ======================================================
     */

    /**
     * activeOptions
     *
     * @return mixed
     */
    public function activeOptions()
    {
        return [
            0 => 'Inactive',
            1 => 'Active'
        ];
    }
}
