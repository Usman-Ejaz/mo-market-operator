<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    use CreatedModifiedBy;

    protected $guarded = [];

    protected $attributes = [];
    
    /**
     * ======================================================
     *                  Model Relations
     * ======================================================
     */

    /**
     * category
     *
     * @return mixed
     */
    public function category() 
    {
        return $this->belongsTo(FaqCategory::class, "category_id", "id");
    }

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
        return ( isset($attribute) ) ? $this->activeOptions()[$attribute] : '';
    }
    
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

    /**
     * ======================================================
     *                  Model Scope Queries
     * ======================================================
     */
    
    /**
     * scopePublished
     *
     * @param  mixed $query
     * @return mixed
     */
    public function scopePublished ($query) 
    {
        return $query->where("published_at", "!=", null)->select("question", "answer");
    }

    /**
     * ======================================================
     *                 Model Helper Functions
     * ======================================================
     */
    
    /**
     * isPublished
     *
     * @return mixed
     */
    public function isPublished()
    {
        return $this->published_at !== null;
    }

    /**
     * activeOptions
     *
     * @return mixed
     */
    public function activeOptions()
    {
        return [
            0 => 'Draft',
            1 => 'Active'
        ];
    }
}
