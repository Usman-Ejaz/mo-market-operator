<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactPageQuery extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STATUS_ENUMS = ["pending", "inprocess", "resolved"];
    
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
     * getStatusAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getStatusAttribute($value)
    {
        return ucfirst($value);
    }
}
