<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MODataFiles extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at', 'id'];
    public const STORAGE_DIRECTORY = 'mo-data/';
    public function moData()
    {
        return $this->belongsTo(MOData::class);
    }
}
