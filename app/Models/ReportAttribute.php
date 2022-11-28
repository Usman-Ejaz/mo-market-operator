<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReportAttribute extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'report_attribute_type_id'];

    public function subCategory()
    {
        return $this->belongsTo(ReportSubCategory::class, 'report_sub_category_id');
    }

    public function type()
    {
        return $this->belongsTo(ReportAttributeType::class, 'report_attribute_type_id');
    }

    public function reports()
    {
        return $this->belongsToMany(Report::class, 'report_attribute_values');
    }
}
