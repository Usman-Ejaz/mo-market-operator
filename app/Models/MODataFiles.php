<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class MODataFiles extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at', 'id'];
    public const STORAGE_DIRECTORY = 'mo-data/';
    public function moData()
    {
        return $this->belongsTo(MOData::class);
    }

    public function scopeForYear($query, $year)
    {
        return $query->whereYear('date', $year);
    }

    public function scopeForMonth($query, $month)
    {
        // $monthNumber = 0;
        try {
            $monthNumber = Carbon::parse($month)->format("m");
        } catch (\Throwable $th) {
            return $query;
        }

        return $query->whereMonth('date', $monthNumber);
    }
}
