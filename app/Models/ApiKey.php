<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ApiKey extends Model
{
    use HasFactory;

    public function scopeValid($query, $name, $value) {
        return $query->where("name", "=", $name)->where("value", "=", $value);
    }
}
