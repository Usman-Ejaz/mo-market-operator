<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatbotInitiator extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * ======================================================
     *                  Model Scope Queries
     * ======================================================
     */
    
    /**
     * scopeFindByEmail
     *
     * @param  mixed $query
     * @param  mixed $email
     * @return mixed
     */
    public function scopeFindByEmail($query, $email)
    {
        return $query->where('email', '=', $email);
    }
    
    /**
     * scopeFindByKey
     *
     * @param  mixed $query
     * @param  mixed $key
     * @return mixed
     */
    public function scopeFindByKey($query, $key)
    {
        return $query->where('token', '=', $key);
    }
}
