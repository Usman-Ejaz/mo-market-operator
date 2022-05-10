<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;

    const STORAGE_DIRECTORY = 'teams/';

    protected $guarded = [];

    public function getCreatedAtAttribute($attribute)
    {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

    public function getImageAttribute($value)
    {
        return $value ? serveFile(self::STORAGE_DIRECTORY, $value) : null;
    }

    public function manager()
    {
        return $this->belongsTo(Manager::class, 'manager_id', 'id');
    }

    public function scopeSortByOrder($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
