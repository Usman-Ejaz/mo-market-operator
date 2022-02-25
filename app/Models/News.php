<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\NewsCategory;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CreatedModifiedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class News extends Model
{
    use HasFactory;

    use CreatedModifiedBy;

    public const STORAGE_DIRECTORY = 'news/';

    protected $guarded = [];

    protected $attributes = [
        // 'active' => 1
    ];

    /********* Getters ***********/
    public function getActiveAttribute($attribute){
        return ( isset($attribute) ) ? $this->activeOptions()[$attribute] : '';
    }

    public function getNewsCategoryAttribute($attribute){
        return ( isset($attribute) && isset( $this->newsCategoryOptions()[$attribute] ) ) ? $this->newsCategoryOptions()[$attribute] : '';
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

    public function newsCategoryOptions(){
        return [
            1 => 'News',
            2 => 'Blog',
            3 => 'Press Release'
        ];
    }

    public function activeOptions(){
        return [
            0 => 'Draft',
            1 => 'Active'
        ];
    }

    // Scope Queries
    public function scopePublished($query) {
        return $query->where("published_at", "!=", null)->select("title", "image", "description", "published_at", "news_category", "slug", "keywords");
    }
}
