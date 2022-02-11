<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscriber extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function getStatusAttribute($attribute) {
        return isset($attribute) ? $this->activeOptions()[$attribute] : '';
    }

    public function scopeActive($query) {
        return $query->where("status", 1);
    }

    private function activeOptions() {
        return [
            0 => 'Unsubscribed',
            1 => 'Subscribed'
        ];
    }
}
