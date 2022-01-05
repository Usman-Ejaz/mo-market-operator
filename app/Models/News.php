<?php

namespace App\Models;

use Carbon\Carbon;
use App\Models\NewsCategory;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use App\Models\Traits\CreatedModifiedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class News extends Model
{
    use HasFactory;

    use CreatedModifiedBy;

    protected $guarded = [];

    protected $attributes = [
        // 'active' => 1
    ];

    /********* Getters ***********/
    public function getActiveAttribute($attribute){
        return $attribute ? $this->activeOptions()[$attribute] : '';
    }

    public function getStartDatetimeAttribute($attribute){
        return $attribute ? Carbon::parse($attribute)->format('d/m/Y H:i:s') : '';
    }

    public function getEndDatetimeAttribute($attribute){
        return $attribute ? Carbon::parse($attribute)->format('d/m/Y H:i:s') : '';
    }


    /********** Setters *********/
    public function setStartDatetimeAttribute($attribute){
     
        $this->attributes['start_datetime'] = ($attribute) ? Carbon::createFromFormat('d/m/Y H:i:s', $attribute) : NULL;
    }

    public function setEndDatetimeAttribute($attribute){

        $this->attributes['end_datetime'] = ($attribute) ? Carbon::createFromFormat('d/m/Y H:i:s', $attribute) : NULL;       
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
