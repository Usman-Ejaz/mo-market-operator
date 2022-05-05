<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    use HasFactory;

    protected $guarded = [];

    const STORAGE_DIRECTORY = 'teams/';

    public function getCreatedAtAttribute($attribute)
    {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.datetime_format')) : '';
    }

    public function getImageAttribute($value)
    {
        return $value ? serveFile(self::STORAGE_DIRECTORY, $value) : null;
    }

    public function removeImage()
    {
        removeFile(self::STORAGE_DIRECTORY, $this->image);
    }

    public function teamMembers()
    {
        return $this->hasMany(TeamMember::class, 'manager_id', 'id');
    }

    public function scopeSortByOrder($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
