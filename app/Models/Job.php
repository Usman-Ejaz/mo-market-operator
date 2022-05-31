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

    /********* Getters ***********/
    public function getActiveAttribute($attribute){
        return ( isset($attribute) ) ? $this->activeOptions()[$attribute] : '';
    }

    public function getStartDatetimeAttribute($attribute){
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

    public function getEndDatetimeAttribute($attribute){
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

    public function getCreatedAtAttribute($attribute){
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

    public function getEnableAttribute($value)
    {
        return $value === 1 ? true : false;
    }

    public function getImageAttribute ($value) {
        return !empty($value) ? serveFile(self::STORAGE_DIRECTORY, $value) : null;
    }

    public function getAttachmentsAttribute($value) {

        return isset($value) ? explode(',', $value) : [];
    }

    public function getAttachmentLinksAttribute($value)
    {
        $links = [];
        
        foreach ($this->attachments as $attachment) {
            array_push($links, serveFile(self::STORAGE_DIRECTORY, $attachment));
        }

        return $links;
    }

    public function getLinkAttribute($value) {
        return !empty($this->slug) ? route('pages.show', $this->slug) : null;
    }

    public function parseStartDate() {
        if ($this->start_datetime) {
            return Carbon::create(str_replace('/', '-', str_replace(' PM', ':00', str_replace(' AM', ':00', $this->start_datetime))));
        }
        return "";
    }

    public function parseEndDate() {
        if ($this->end_datetime) {
            return Carbon::create(str_replace('/', '-', str_replace(' PM', ':00', str_replace(' AM', ':00', $this->end_datetime))));
        }
        return "";
    }


    /********** Setters *********/
    // public function setStartDatetimeAttribute($attribute){
    //     $this->attributes['start_datetime'] = ($attribute) ? Carbon::createFromFormat(config('settings.datetime_format'), $attribute) : NULL;
    // }

    // public function setEndDatetimeAttribute($attribute){
    //     $this->attributes['end_datetime'] = ($attribute) ? Carbon::createFromFormat(config('settings.datetime_format'), $attribute) : NULL;
    // }

    public function activeOptions(){
        return [
            0 => 'Draft',
            1 => 'Active'
        ];
    }

    // Relations
    public function applications() {
        return $this->hasMany(Application::class)->orderBy('created_at', 'desc');
    }

    // Scope Queries
    public function scopePublished ($query) {
        return $query->where("published_at", "!=", null)->select("title", "slug", "description", "location", "salary", "enable", "qualification", "experience", "published_at", "total_positions", "image", "attachments");
    }

    public function scopeApplyFilters($query)
    {
        $request = request();

        if ($request->has('month')) {
            $query = $query->whereMonth('created_at', '=', $request->get('month'));
        }

        if ($request->has('year')) {
            $query = $query->whereYear('created_at', '=', $request->get('year'));
        }

        if ($request->has('sort')) {
            $query = $query->orderBy('created_at', $request->get('sort'));
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
        return $query->where('start_datetime', '!=', NULL)->where('end_datetime', '!=', NULL);
    }
    
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
}
