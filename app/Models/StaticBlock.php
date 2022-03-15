<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaticBlock extends Model
{
    use HasFactory;

    protected $guarded = [];

        
    /**
     * getCreatedAtAttribute
     *
     * @param  string $value
     * @return string
     */
    public function getCreatedAtAttribute($value): string
    {
        return $value ? Carbon::parse($value)->format(config('settings.datetime_format')) : '';
    }
}
