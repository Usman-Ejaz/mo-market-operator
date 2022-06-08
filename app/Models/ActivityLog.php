<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */
    
    /**
     * getDoneByAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getDoneByAttribute($value)
    {
        return !empty($value) ? User::find($value)->name : $value;
    }
    
    /**
     * getCreatedAtAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('settings.datetime_format')) : '';
    }
}
