<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getStatusAttribute($attribute) 
    {
        return isset($attribute) ? $this->activeOptions()[$attribute] : '';
    }

    public function getRssFeedAttribute($attribute) 
    {
        return isset($attribute) ? $this->activeOptions()[$attribute] : '';
    }

    public function getCreatedAtAttribute($attribute){
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

    public function scopeNewletters($query) {
        return $query->where("status", 1);
    }

    private function activeOptions() {
        return [
            0 => 'Unsubscribed',
            1 => 'Subscribed'
        ];
    }
}
