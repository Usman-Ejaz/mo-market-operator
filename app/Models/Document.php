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

    // Model Relation goes here

    public function category() {
        return $this->belongsTo(DocumentCategory::class, "category_id", "id");
    }

    /********** Setters *********/
    public function setKeywordsAttribute($attribute){

        $this->attributes['keywords'] = ($attribute) ? trim($attribute, ', ') : NULL;
    }

    public function getCreatedAtAttribute($attribute){
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }
    
    /**
     * getFileAttribute
     *
     * @param  mixed $value
     * @return void
     */
    public function getFileAttribute($value)
    {
        return !empty($value) ? explode(",", $value) : [];
    }
    
    /**
     * getDocumentLinksAttribute
     *
     * @return void
     */
    public function getDocumentLinksAttribute()
    {
        $filePaths = [];
        foreach($this->file as $filename) {
            array_push($filePaths, serveFile(self::STORAGE_DIRECTORY, $filename));
        }    
        return $filePaths;
    }

    // Scope Queries
    public function scopePublished ($query) {
        return $query->where("published_at", "!=", null)->select("title", "file", "keywords", "category_id");
    }

    public function isPublished() {
        return $this->published_at !== null;
    }
}
