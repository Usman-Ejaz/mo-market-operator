<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotInitiator extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function scopeFindByEmail($query, $email)
    {
        return $query->where('email', '=', $email);
    }

    public function scopeFindByKey($query, $key)
    {
        return $query->where('token', '=', $key);
    }
}
