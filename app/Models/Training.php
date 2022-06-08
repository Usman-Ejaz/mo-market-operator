<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasFactory, CreatedModifiedBy;

    protected $guarded = [];

    const STORAGE_DIRECTORY = 'trainings/';

    const STATUS_ENUMS = ["open", "closed"];

    protected $appends = ['attachment_links'];


    /**
     * ======================================================
     *                 Model Mutator Functions
     * ======================================================
     */
    
    /**
     * setStartDateAttribute
     *
     * @param  mixed $value
     * @return mixed
     */
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = parseDate($value);
    }
    
    /**
     * setEndDateAttribute
     *
     * @param  string $value
     * @return mixed
     */
    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = parseDate($value);
    }

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */

    /**
     * getCreatedAtAttribute
     *
     * @param  string $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('settings.datetime_format')) : '';
    }
    
    /**
     * getAttachmentAttribute
     *
     * @param  string $value
     * @return array
     */
    public function getAttachmentAttribute($value)
    {
        return $value ? explode(",", $value) : [];
    }
    
    /**
     * getAttachmentLinkAttribute
     *
     * @return array
     */
    public function getAttachmentLinksAttribute()
    {
        if ($this->attachment === null) return [];

        $links = [];
        foreach ($this->attachment as $file) {
            array_push($links, serveFile(self::STORAGE_DIRECTORY, $file));
        }
        return $links;
    }
    
    /**
     * getStartDateAttribute
     *
     * @param  string $value
     * @return string
     */
    public function getStartDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('settings.datetime_format')) : '';
    }
    
    /**
     * getEndDateAttribute
     *
     * @param  string $value
     * @return string
     */
    public function getEndDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('settings.datetime_format')) : '';
    }


    /**
     * ======================================================
     *               Model Scope Query Functions
     * ======================================================
     */
    
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
            $query = $query->whereMonth('created_at', '=', $request->get('month'));
        }

        if ($request->has('year')) {
            $query = $query->whereYear('created_at', '=', $request->get('year'));
        }

        if ($request->has('sort')) {
            $query = $query->orderBy('created_at', $request->get('sort'));
        }

        return $query;
    }


    /**
     * ======================================================
     *                 Model Helper Functions
     * ======================================================
     */

    /**
     * status
     *
     * @return string
     */
    public function status()
    {
        return ucfirst($this->status);
    }
    
    /**
     * removeAttachments
     *
     * @return mixed
     */
    public function removeAttachments()
    {
        if (count($this->attachment)) {
            foreach ($this->attachment as $file) {
                removeFile(self::STORAGE_DIRECTORY, $file);
            }
        }
    }
}
