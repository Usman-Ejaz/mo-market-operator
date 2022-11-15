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

    protected $attributes = [];

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
        return (isset($attribute)) ? $this->activeOptions()[$attribute] : '';
    }

    /**
     * getPostCategoryAttribute
     *
     * @param  mixed $attribute
     * @return mixed
     */
    public function getPostCategoryAttribute($attribute)
    {
        return (isset($attribute) && isset($this->postCategoryOptions()[$attribute])) ? $this->postCategoryOptions()[$attribute] : '';
    }

    /**
     * getStartDatetimeAttribute
     *
     * @param  mixed $attribute
     * @return mixed
     */
    public function getStartDatetimeAttribute($attribute)
    {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

    /**
     * getEndDatetimeAttribute
     *
     * @param  mixed $attribute
     * @return mixed
     */
    public function getEndDatetimeAttribute($attribute)
    {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

    /**
     * getCreatedAtAttribute
     *
     * @param  mixed $attribute
     * @return mixed
     */
    public function getCreatedAtAttribute($attribute)
    {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.createdat_datetime_format')) : '';
    }

    /**
     * getImageAttribute
     *
     * @param  mixed $value
     * @return mixed
     */
    public function getImageAttribute($value)
    {
        return serveFile(self::STORAGE_DIRECTORY, $value);
    }

    /**
     * getLinkAttribute
     *
     * @return mixed
     */
    public function getLinkAttribute()
    {
        return isset($this->slug) ? config('settings.client_app_base_url') . Str::plural(strtolower($this->post_category)) . '/' . $this->slug : null;
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
     * @return mixed
     */
    public function setKeywordsAttribute($attribute)
    {
        $this->attributes['keywords'] = ($attribute) ? trim($attribute, ', ') : NULL;
    }

    /**
     * setSlugAttribute
     *
     * @param  mixed $attribute
     * @return mixed
     */
    public function setSlugAttribute($attribute)
    {
        $this->attributes['slug'] = ($attribute) ? strtolower(trim($attribute, '- ')) : NULL;
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
    public function scopePublished($query)
    {
        return $query->where("published_at", "!=", null)->select("title", "image", "description", "published_at", "post_category", "slug", "keywords", "created_by");
    }

    public function scopeForCategorySlug($query, $slug)
    {
        $categoryName = Str::title(str_replace("-", " ", $slug));
        $postCategoryID = collect($this->postCategoryOptions())->search($categoryName);
        return $query->where('post_category', '=', $postCategoryID);
    }


    /**
     * scopeNews
     *
     * @param  mixed $query
     * @return mixed
     */
    public function scopeNews($query)
    {
        return $query->where('post_category', '=', 1);
    }

    /**
     * scopeBlogs
     *
     * @param  mixed $query
     * @return mixed
     */
    public function scopeBlogs($query)
    {
        return $query->where('post_category', '=', 2);
    }

    /**
     * scopeNewsAndBlogs
     *
     * @param  mixed $query
     * @return mixed
     */
    public function scopeNewsAndBlogs($query)
    {
        return $query->whereIn('post_category', [1, 2]);
    }

    /**
     * scopeAnnouncements
     *
     * @param  mixed $query
     * @return mixed
     */
    public function scopeAnnouncements($query)
    {
        return $query->where('post_category', '=', 3);
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
     * @return boolean
     */
    public function isPublished()
    {
        return $this->published_at !== null;
    }

    /**
     * postCategoryOptions
     *
     * @return mixed
     */
    public function postCategoryOptions()
    {
        return [
            1 => 'News',
            2 => 'Strategic Partnerships',
            3 => 'Announcements'
        ];
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

    /**
     * parseStartDate
     *
     * @return mixed
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
     * @return mixed
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
