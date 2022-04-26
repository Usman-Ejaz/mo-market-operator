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

    public function getCreatedAtAttribute($attribute){
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }    
}
