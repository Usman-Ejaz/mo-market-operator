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
    
    public function getActiveAttribute ($attribute) {
        return isset($attribute) ? $this->activeOptions()[$attribute] : '';
    }

    public function getStartDatetimeAttribute($attribute) {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

    public function getEndDatetimeAttribute($attribute){
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

    public function getCreatedAtAttribute($attribute){
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

    public function getImageAttribute ($value) {
        return !empty($value) ? asset(config("filepaths.pageImagePath.public_path") . $value) : null;
    }

    public function getLinkAttribute ($value) {
        return !empty($this->slug) ? route('pages.show', $this->slug) : null;
    }


    /********** Setters *********/
    public function setStartDatetimeAttribute($attribute){

        $this->attributes['start_datetime'] = ($attribute) ? Carbon::createFromFormat(config('settings.datetime_format'), $attribute) : NULL;
    }

    public function setEndDatetimeAttribute($attribute){

        $this->attributes['end_datetime'] = ($attribute) ? Carbon::createFromFormat(config('settings.datetime_format'), $attribute) : NULL;
    }

    public function setKeywordsAttribute($attribute){

        $this->attributes['keywords'] = ($attribute) ? trim($attribute, ', ') : NULL;
    }

    public function setSlugAttribute($attribute){

        $this->attributes['slug'] = ($attribute) ? trim($attribute, '- ') : NULL;
    }
    
    public function activeOptions(){
        return [
            0 => 'Draft',
            1 => 'Active'
        ];
    }

    // Scope Queries
    public function scopePublished($query) {
        return $query->where('published_at', '!=', null)->select('title', 'slug', 'keywords', 'description');
    }
}
