<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    use CreatedModifiedBy;

    protected $guarded = [];

    protected $attributes = [];

    protected $appends = ['document_links'];

    public const STORAGE_DIRECTORY = 'documents/';

    /**
     * ======================================================
     *                  Model Relations
     * ======================================================
     */

    /**
     * category
     *
     * @return mixed
     */
    public function category()
    {
        return $this->belongsTo(DocumentCategory::class, "category_id", "id");
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
     *                  Model Mutators Queries
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
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */

    /**
     * getCreatedAtAttribute
     *
     * @param  mixed $attribute
     * @return mixed
     */
    public function getCreatedAtAttribute($attribute)
    {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

    /**
     * getImageAttribute
     *
     * @param  mixed $value
     * @return mixed
     */
    public function getImageAttribute($value)
    {
        return $value ? serveFile(Document::STORAGE_DIRECTORY, $value) : null;
    }

    /**
     * getFileAttribute
     *
     * @param  mixed $value
     * @return mixed
     */
    public function getFileAttribute($value)
    {
        return !empty($value) ? explode(",", $value) : [];
    }

    /**
     * getDocumentLinksAttribute
     *
     * @return mixed
     */
    public function getDocumentLinksAttribute()
    {
        $filePaths = [];
        foreach($this->file as $filename) {
            array_push($filePaths, serveFile(self::STORAGE_DIRECTORY, $filename));
        }
        return $filePaths;
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
    public function scopePublished ($query)
    {
        return $query->where("published_at", "!=", null)->select("title", "file", "keywords", "category_id", "published_at", "image", "slug");
    }

    /**
     * scopeFilterByCategory
     *
     * @param  mixed $query
     * @param  mixed $category
     * @return mixed
     */
    public function scopeFilterByCategory($query, $category)
    {
        return $query->whereHas('category', function ($q) use ($category) {
            $q->where('slug', '=', $category);
        });
    }

    /**
     * scopeApplyFilters
     *
     * @param  mixed $query
     * @param  mixed $request
     * @return mixed
     */
    public function scopeApplyFilters($query, $request)
    {
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
     * ======================================================
     *                 Model Helper Functions
     * ======================================================
     */

    /**
     * isPublished
     *
     * @return mixed
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
}
