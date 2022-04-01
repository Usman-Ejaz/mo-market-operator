<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SearchStatistic extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Getters
    public function getCreatedAtAttribute ($value) {
        return $value ? Carbon::parse($value)->format(config('settings.datetime_format')) : '';
    }

    // Scope Queries
    public function scopeOrderByCount($query) {
        return $query->orderBy('count', 'desc');
    }

    public function scopeGroupByKeyword($query, $startFrom = null, $endsAt = null)
    {
        if ($startFrom !== null) {
            $query->where('created_at', '>=', Carbon::parse($startFrom));
        }

        if ($endsAt !== null) {
            $query->where('created_at', '<=',  Carbon::parse($endsAt));
        }

        return $query;
    }
}
