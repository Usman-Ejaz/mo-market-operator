<?php

namespace App\Models;

use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $attributes = [];

    public const STORAGE_DIRECTORY = 'applications/';

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
     * getResumeAttribute
     *
     * @param  mixed $value
     * @return mixed
     */
    public function getResumeAttribute($value)
    {
        return !empty($value) ? serveFile(self::STORAGE_DIRECTORY, $value) : null;
    }

    /**
     * ======================================================
     *                  Model Relations
     * ======================================================
     */
        
    /**
     * applicationsJob
     *
     * @return mixed
     */
    public function applicationsJob()
    {        
        return $this->belongsTo(Job::class);
    }

    
}