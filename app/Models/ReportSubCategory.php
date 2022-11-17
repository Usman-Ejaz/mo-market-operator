<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportSubCategory extends Model
{
    use HasFactory;

    protected $guarded = ['created_at', 'updated_at', 'id'];

    public function category()
    {
        return $this->belongsTo(ReportCategory::class, 'report_category_id');
    }

    public function attributes()
    {
        return $this->hasMany(ReportAttribute::class);
    }

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
