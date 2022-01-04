<?php

namespace App\Models;

use App\Models\NewsCategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class News extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $attributes = [
         'active' => 1
    ];

    public function getActiveAttribute($attribute){
        return $this->activeOptions()[$attribute];
    }

    public function newscategory(){
        return $this->belongsTo(NewsCategory::class);
    }

    public function activeOptions(){
        return [
            0 => 'Draft',
            1 => 'Active'
        ];
    }
}
