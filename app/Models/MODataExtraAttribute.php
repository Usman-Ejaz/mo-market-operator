<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MODataExtraAttribute extends Model
{
    use HasFactory;
    protected $guarded = ['created_at', 'updated_at', 'id'];

    public function moData()
    {
        return $this->belongsTo(MOData::class);
    }
}
