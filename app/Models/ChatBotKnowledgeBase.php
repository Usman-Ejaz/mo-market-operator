<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatBotKnowledgeBase extends Model
{
    use HasFactory;

    protected $guarded = [];

    // Setters
    public function getCreatedAtAttribute ($value) {
        return $value ? Carbon::parse($value)->format(config('settings.datetime_format')) : '';
    }
}
