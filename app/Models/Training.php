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
    public function setStartDatetimeAttribute($value)
    {
        $this->attributes['start_datetime'] = parseDate($value);
    }

    /**
     * setEndDateAttribute
     *
     * @param  string $value
     * @return mixed
     */
    public function setEndDatetimeAttribute($value)
    {
        $this->attributes['end_datetime'] = parseDate($value);
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
    public function getStartDatetimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('settings.datetime_format')) : '';
    }

    /**
     * getEndDateAttribute
     *
     * @param  string $value
     * @return string
     */
    public function getEndDatetimeAttribute($value)
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
     * scopeScheduledRecords
     *
     * @param  mixed $query
     * @return void
     */
    public function scopeScheduledRecords($query)
    {
        return $query
            ->where('start_datetime', '!=', null)
            ->where('end_datetime', '!=', null);
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
        return $this->status == '1' ? __('Open') : __('Closed');
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
