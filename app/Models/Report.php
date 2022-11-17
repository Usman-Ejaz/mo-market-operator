<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'publish_date', 'report_sub_category_id'];
    protected $casts = ['publish_date' => 'date'];

    public function subCategory()
    {
        return $this->belongsTo(ReportSubCategory::class, 'report_sub_category_id');
    }

    public function filledAttributes()
    {
        return $this->belongsToMany(ReportAttribute::class, 'report_attribute_values')->withPivot(['value'])->withTimestamps();
    }

    public function attachments()
    {
        return $this->hasMany(ReportAttachment::class);
    }
}
