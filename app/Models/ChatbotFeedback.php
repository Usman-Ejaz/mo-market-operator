<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotFeedback extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $table = 'feedback_ratings';

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     * */

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
     * ======================================================
     *                 Model Relation Functions
     * ======================================================
     * */

    /**
     * getCreatedAtAttribute
     *
     */
    public function owner()
    {
        return $this->belongsTo(ChatbotInitiator::class, 'chatbot_initiator_id', 'id');
    }
}
