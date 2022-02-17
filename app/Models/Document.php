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

    public function getFileAttribute ($value) {
        return !empty($value) ? asset(config('filepaths.documentsFilePath.internal_path') . $value) : null;
    }

    // Scope Queries
    public function scopePublished ($query) {
        return $query->where("published_at", "!=", null)->select("title", "file", "keywords", "category_id");
    }
}
