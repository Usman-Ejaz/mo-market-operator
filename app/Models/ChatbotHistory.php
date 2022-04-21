<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotHistory extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeTodaysChat($query, $initiatorId)
    {
        return $query->where('chatbot_initiator_id', '=', $initiatorId)->where('created_at', '>=', Carbon::today())->where('created_at', '<', Carbon::tomorrow());
    }
}
