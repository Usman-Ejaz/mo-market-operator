<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchStatistic extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */

    /**
     * getCreatedAtAttribute
     *
     * @param  mixed $value
     * @return mixed
     */
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('settings.createdat_datetime_format')) : '';
    }

    /**
     * ======================================================
     *                  Model Scope Queries
     * ======================================================
     */

    /**
     * scopeOrderByCount
     *
     * @param  mixed $query
     * @return mixed
     */
    public function scopeOrderByCount($query)
    {
        return $query->orderBy('count', 'desc');
    }

    /**
     * scopeGroupByKeyword
     *
     * @param  mixed $query
     * @param  mixed $startFrom
     * @param  mixed $endsAt
     * @return mixed
     */
    public function scopeGroupByKeyword($query, $startFrom = null, $endsAt = null)
    {
        if ($startFrom !== null) {
            $query->where('created_at', '>=', Carbon::parse($startFrom));
        }

        if ($endsAt !== null) {
            $query->where('created_at', '<=',  Carbon::parse($endsAt));
        }

        return $query->groupBy('keyword')->selectRaw('keyword, sum(count) as count_sum');
    }
}
