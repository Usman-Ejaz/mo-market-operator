<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    use HasFactory, CreatedModifiedBy;

    protected $guarded = [];

    protected $attributes = [];

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
}
