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

    protected $appends = ['link'];

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

    public function getImageAttribute ($value) {
        return serveFile(self::STORAGE_DIRECTORY, $value);
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
        return $query->where("published_at", "!=", null)->select("title", "slug", "description", "location", "qualification", "experience", "published_at", "total_positions", "image");
    }

    public function isPublished() {
        return $this->published_at !== null;
    }
}
