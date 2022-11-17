<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportCategory extends Model
{
    use HasFactory;
    protected $guarded = ['created_at', 'updated_at', 'id'];

    public function subCategories()
    {
        return $this->hasMany(ReportSubCategory::class);
    }

    public function reports()
    {
        return $this->hasManyThrough(Report::class, ReportSubCategory::class);
    }
}
