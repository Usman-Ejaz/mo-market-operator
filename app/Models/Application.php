<?php

namespace App\Models;

use App\Models\Job;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $attributes = [];

    /********** Setters *********/

    public function applicationsJob(){
        
        return $this->belongsTo(Job::class);
    }
}