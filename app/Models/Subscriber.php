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

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */
    
    /**
     * getStatusAttribute
     *
     * @param  mixed $attribute
     * @return void
     */
    public function getStatusAttribute($attribute) 
    {
        return isset($attribute) ? $this->activeOptions()[$attribute] : '';
    }
    
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
     * ======================================================
     *                  Model Scope Queries
     * ======================================================
     */
    
    /**
     * scopeNewletters
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeNewletters($query) 
    {
        return $query->where("status", 1);
    }

    /**
     * ======================================================
     *                 Model Helper Functions
     * ======================================================
     */
    
    /**
     * activeOptions
     *
     * @return void
     */
    private function activeOptions() 
    {
        return [
            0 => 'Unsubscribed',
            1 => 'Subscribed'
        ];
    }
}
