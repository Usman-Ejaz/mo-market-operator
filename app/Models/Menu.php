<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $guarded = [];

    /********* Getters ***********/
    public function getActiveAttribute($attribute){
        return ( isset($attribute) ) ? $this->activeOptions()[$attribute] : '';
    }

    public function getCreatedAtAttribute($attribute){
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

    public function activeOptions(){
        return [
            0 => 'Inactive',
            1 => 'Active'
        ];
    }

    public function scopeByTheme($query, $theme = null)
    {
        if ($theme === null) {
            $theme = Settings::where('name', 'current_theme')->first();
            return $query->where('theme', $theme->value);
        } else {
            return $query->where('theme', $theme);
        }
    }
}
