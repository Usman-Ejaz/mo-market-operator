<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Application;

class Job extends Model
{
    use HasFactory;

    use CreatedModifiedBy;

    protected $guarded = [];

    protected $attributes = [
        // 'active' => 1
    ];

    /********* Getters ***********/
    public function getActiveAttribute($attribute){
        return ( isset($attribute) ) ? $this->activeOptions()[$attribute] : '';
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

    public function applications(){
        return $this->hasMany(Application::class);
    }

    public function activeOptions(){
        return [
            0 => 'Draft',
            1 => 'Active'
        ];
    }
}
