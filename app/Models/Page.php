<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    use HasFactory, CreatedModifiedBy;

    protected $guarded = [];

    protected $attributes = [ /*'active' => 1*/];

    protected $appends = ['link'];

    public const STORAGE_DIRECTORY = 'pages/';

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
    public function getActiveAttribute ($attribute) 
    {
        return isset($attribute) ? $this->activeOptions()[$attribute] : '';
    }
    
    /**
     * getStartDatetimeAttribute
     *
     * @param  mixed $attribute
     * @return void
     */
    public function getStartDatetimeAttribute($attribute) 
    {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }
    
    /**
     * getEndDatetimeAttribute
     *
     * @param  mixed $attribute
     * @return void
     */
    public function getEndDatetimeAttribute($attribute)
    {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
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
     * getImageAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getImageAttribute ($value) 
    {
        return !empty($value) ? serveFile(self::STORAGE_DIRECTORY, $value) : null;
    }
    
    /**
     * getLinkAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getLinkAttribute ($value) 
    {
        return !empty($this->slug) ? config('settings.client_app_base_url') . $this->slug : null;
    }

    /**
     * ======================================================
     *                 Model Mutator Functions
     * ======================================================
     */
    
    /**
     * setKeywordsAttribute
     *
     * @param  mixed $attribute
     * @return void
     */
    public function setKeywordsAttribute($attribute)
    {
        $this->attributes['keywords'] = ($attribute) ? trim($attribute, ', ') : NULL;
    }
    
    /**
     * setSlugAttribute
     *
     * @param  mixed $attribute
     * @return void
     */
    public function setSlugAttribute($attribute)
    {
        $this->attributes['slug'] = ($attribute) ? str_slug($attribute, '- ') : NULL;
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
     * @return void
     */
    public function scopePublished($query) 
    {
        return $query->where('published_at', '!=', null)->select('title', 'slug', 'keywords', 'description', 'image');
    }

    /**
     * scopeScheduledRecords
     *
     * @param  mixed $query
     * @return mixed
     */
    public function scopeScheduledRecords($query)
    {
        return $query->where('start_datetime', '!=', NULL);
    }
    
    /**
     * scopeTodaysPublishedRecords
     *
     * @param  mixed $query
     * @return mixed
     */
    public function scopeTodaysPublishedRecords($query)
    {
        return $query->whereDate('published_at', Carbon::today());
    }

    /**
     * ======================================================
     *                 Model Helper Functions
     * ======================================================
     */
    
    /**
     * isPublished
     *
     * @return void
     */
    public function isPublished() 
    {
        return $this->published_at !== null;
    }

    /**
     * activeOptions
     *
     * @return void
     */
    public function activeOptions()
    {
        return [
            0 => 'Draft',
            1 => 'Active'
        ];
    }

    /**
     * parseStartDate
     *
     * @return void
     */
    public function parseStartDate() 
    {
        if ($this->start_datetime) {
            return Carbon::create(str_replace('/', '-', str_replace(' PM', ':00', str_replace(' AM', ':00', $this->start_datetime))));
        }
        return "";
    }
    
    /**
     * parseEndDate
     *
     * @return void
     */
    public function parseEndDate() 
    {
        if ($this->end_datetime) {
            return Carbon::create(str_replace('/', '-', str_replace(' PM', ':00', str_replace(' AM', ':00', $this->end_datetime))));
        }
        return "";
    }    

    /**
     * ======================================================
     *                  Model Relations
     * ======================================================
     */

    /**
     * author
     *
     * @param  mixed $value
     * @return mixed
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
