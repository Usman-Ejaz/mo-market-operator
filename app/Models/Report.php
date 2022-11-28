<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    use HasFactory;

    protected $fillable = ['name', 'publish_date', 'report_sub_category_id'];
    protected $casts = ['publish_date' => 'date'];

    public function subCategory()
    {
        return $this->belongsTo(ReportSubCategory::class, 'report_sub_category_id');
    }

    public function category()
    {
        return $this->belongsToThrough(ReportCategory::class, ReportSubCategory::class);
    }

    public function filledAttributes()
    {
        return $this->belongsToMany(ReportAttribute::class, 'report_attribute_values')->withPivot(['value'])->withTimestamps();
    }

    public function attachments()
    {
        return $this->hasMany(ReportAttachment::class);
    }

    public function scopeForCategory($query, iterable $categories)
    {
        return $query->whereHas('category', function ($q) use (&$categories) {
            return $q->whereIn('report_categories.name', $categories);
        });
    }

    public function scopeForSubCategory($query, iterable $subCategories)
    {
        $query->whereHas('subCategory', function ($q) use (&$subCategories) {
            return $q->whereIn('report_sub_categories.name', $subCategories);
        });
    }

    public function scopeAttributeWithValue($query, string $attribute, string $value)
    {
        return $query->whereHas('filledAttributes', function ($q) use (&$attribute, &$value) {
            return $q->where('name', $attribute)->where('report_attribute_values.value', $value);
        });
    }
}
