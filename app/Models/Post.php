<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Post extends Model
{
    use HasFactory;

    use CreatedModifiedBy;

    public const STORAGE_DIRECTORY = 'posts/';

    protected $table = "posts";

    protected $appends = ['link'];

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

    public function getLinkAttribute()
    {
        return isset($this->slug) ? config('settings.client_app_base_url') . Str::plural(strtolower($this->post_category)) . '/' . $this->slug : null;
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
            3 => 'Announcements'
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
    
    /**
     * scopeNews
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeNews($query)
    {
        return $query->where('post_category', '=', 1);
    }
    
    /**
     * scopeBlogs
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeBlogs($query)
    {
        return $query->where('post_category', '=', 2);
    }
    
    /**
     * scopeNewsAndBlogs
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeNewsAndBlogs($query)
    {
        return $query->whereIn('post_category', [1, 2]);
    }
    
    /**
     * scopeAnnouncements
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeAnnouncements($query)
    {
        return $query->where('post_category', '=', 3);
    }
    
    /**
     * isPublished
     *
     * @return boolean
     */
    public function isPublished() {
        return $this->published_at !== null;
    }
    
    /**
     * scopeApplyFilters
     *
     * @param  mixed $query
     * @return mixed
     */
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
     * scopeTodaysPublishedRecords
     *
     * @param  mixed $query
     * @return mixed
     */
    public function scopeTodaysPublishedRecords($query)
    {
        return $query->whereDay('published_at', date('d'));
    }
}
