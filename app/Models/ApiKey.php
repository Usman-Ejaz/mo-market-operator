<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * ======================================================
     *                  Model Scope Queries
     * ======================================================
     */

    /**
     * scopeValid
     *
     * @param  mixed $query
     * @param  mixed $name
     * @param  mixed $value
     * @return mixed
     */
    public function scopeValid($query, $name, $value)
    {
        return $query->where("name", "=", $name)->where("value", "=", $value);
    }
}
