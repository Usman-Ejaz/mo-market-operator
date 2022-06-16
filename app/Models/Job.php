<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;

class Job extends Model
{
    use HasFactory, CreatedModifiedBy;

    protected $guarded = [];

    protected $table = "job_posts";

    protected $attributes = [
        // 'active' => 1
    ];

    const STORAGE_DIRECTORY = 'jobs/';

    protected $appends = ['attachment_links'];
    
    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */
        
    /**
     * getActiveAttribute
     *
     * @param  mixed $attribute
     * @return void
     */
    public function getActiveAttribute($attribute)
    {
        return ( isset($attribute) ) ? $this->activeOptions()[$attribute] : '';
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
     * getEnableAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getEnableAttribute($value)
    {
        return $value === 1;
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
     * getAttachmentsAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getAttachmentsAttribute($value) 
    {
        return isset($value) ? explode(',', $value) : [];
    }
    
    /**
     * getAttachmentLinksAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getAttachmentLinksAttribute($value)
    {
        $links = [];
        
        foreach ($this->attachments as $attachment) {
            array_push($links, serveFile(self::STORAGE_DIRECTORY, $attachment));
        }

        return $links;
    }
    
    /**
     * getLinkAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getLinkAttribute($value) 
    {
        return $value;
    }        
    
    /**
     * ======================================================
     *                  Model Relations
     * ======================================================
     */

    /**
     * applications
     *
     * @return void
     */
    public function applications() 
    {
        return $this->hasMany(Application::class)->orderBy('created_at', 'desc');
    }

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
    public function scopePublished ($query) 
    {
        return $query->where("published_at", "!=", null)->select("title", "slug", "short_description", "description", "location", "salary", "enable", "qualification", "experience", "published_at", "total_positions", "image", "attachments");
    }
    
    /**
     * scopeApplyFilters
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeApplyFilters($query)
    {
        $request = request();

        if ($request->has('month')) {
            $query = $query->whereMonth('published_at', '=', $request->get('month'));
        }

        if ($request->has('year')) {
            $query = $query->whereYear('published_at', '=', $request->get('year'));
        }

        if ($request->has('sort')) {
            $query = $query->orderBy('published_at', $request->get('sort'));
        }

        return $query;
    }
    
    /**
     * scopeScheduledRecords
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeScheduledRecords($query)
    {
        return $query->where('start_datetime', '!=', NULL);
    }

    /**
     * ======================================================
     *                 Model Helper Functions
     * ======================================================
     */
    
    /**
     * isPublished
     *
     * @return boolean true|false
     */
    public function isPublished() 
    {
        return $this->published_at !== null;
    }
    
    /**
     * removeImage
     *
     * @return void
     */
    public function removeImage()
    {
        removeFile(self::STORAGE_DIRECTORY, $this->image);
    }
    
    /**
     * removeAttachments
     *
     * @return void
     */
    public function removeAttachments()
    {
        foreach ($this->attachments as $file) 
        {
            removeFile(self::STORAGE_DIRECTORY, $file);
        }
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
     * activeOptions
     *
     * @return void
     */
    public function activeOptions(){
        return [
            0 => 'Draft',
            1 => 'Active'
        ];
    }
}
