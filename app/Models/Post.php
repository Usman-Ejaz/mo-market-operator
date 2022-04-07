<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    use CreatedModifiedBy;

    public const STORAGE_DIRECTORY = 'posts/';

    protected $table = "posts";

    protected $guarded = [];

    protected $attributes = [
        // 'active' => 1
    ];

    /********* Getters ***********/
    public function getActiveAttribute($attribute){
        return ( isset($attribute) ) ? $this->activeOptions()[$attribute] : '';
    }

    public function getPostCategoryAttribute($attribute){
        return ( isset($attribute) && isset( $this->postCategoryOptions()[$attribute] ) ) ? $this->postCategoryOptions()[$attribute] : '';
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
    //     $attribute = str_replace(' PM', '', str_replace(' AM', '', $attribute)) . ":00";
    //     $this->attributes['start_datetime'] = ($attribute) ? Carbon::createFromFormat(config('settings.datetime_format'), $attribute) : NULL;
    // }

    // public function setEndDatetimeAttribute($attribute){

    //     $this->attributes['end_datetime'] = ($attribute) ? Carbon::createFromFormat(config('settings.datetime_format'), $attribute) : NULL;
    // }

    public function setKeywordsAttribute($attribute){

        $this->attributes['keywords'] = ($attribute) ? trim($attribute, ', ') : NULL;
    }

    public function setSlugAttribute($attribute){

        $this->attributes['slug'] = ($attribute) ? strtolower(trim($attribute, '- ')) : NULL;
    }

    public function postCategoryOptions(){
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
        return $query->where("published_at", "!=", null)->select("title", "image", "description", "published_at", "post_category", "slug", "keywords");
    }

    public function scopeOnlyNewsAndBlogs($query)
    {
        return $query->whereIn('post_category', [1, 2]);
    }

    public function scopeOnlyPressRelease($query)
    {
        return $query->where('post_category', '=', 3);
    }

    public function isPublished() {
        return $this->published_at !== null;
    }
}
