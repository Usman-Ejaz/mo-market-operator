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
        return $value ? asset(config("filepaths.jobImagePath.public_path") . $value) : null;
    }


    /********** Setters *********/
    public function setStartDatetimeAttribute($attribute){
        $this->attributes['start_datetime'] = ($attribute) ? Carbon::createFromFormat(config('settings.datetime_format'), $attribute) : NULL;
    }

    public function setEndDatetimeAttribute($attribute){
        $this->attributes['end_datetime'] = ($attribute) ? Carbon::createFromFormat(config('settings.datetime_format'), $attribute) : NULL;
    }

    public function activeOptions(){
        return [
            0 => 'Draft',
            1 => 'Active'
        ];
    }

    // Relations
    public function applications() {
        return $this->hasMany(Application::class);
    }

    // Scope Queries
    public function scopePublished ($query) {
        return $query->where("published_at", "!=", null)->select("title", "slug", "description", "location", "qualification", "experience", "total_positions", "image", "start_datetime", "end_datetime");
    }
}
