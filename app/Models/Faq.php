<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;
    use CreatedModifiedBy;

    protected $guarded = [];

    protected $attributes = [];

    public function category() {
        return $this->belongsTo(FaqCategory::class, "category_id", "id");
    }

    /********* Getters ***********/
    public function getActiveAttribute($attribute){
        return ( isset($attribute) ) ? $this->activeOptions()[$attribute] : '';
    }

    public function getCreatedAtAttribute($attribute){
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

    public function activeOptions(){
        return [
            0 => 'Draft',
            1 => 'Active'
        ];
    }

    // Scope Queries
    public function scopePublished ($query) {
        return $query->where("published_at", "!=", null)->select("question", "answer");
    }

    public function isPublished()
    {
        return $this->published_at !== null;
    }
}
