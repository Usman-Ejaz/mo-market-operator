<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Report extends Model
{
    use \Znck\Eloquent\Traits\BelongsToThrough;

    use HasFactory;

    protected $fillable = ['name', 'publish_date', 'report_sub_category_id'];
    // protected $casts = ['publish_date' => 'date'];

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

    public function scopeForSubCategoryIDs($query, iterable $subCategorieIDs)
    {
        return $query->whereHas('subCategory', function ($q) use (&$subCategorieIDs) {
            return $q->whereIn('report_sub_categories.id', $subCategorieIDs);
        });
    }

    public function scopeForSubCategory($query, iterable $subCategories)
    {
        return $query->whereHas('subCategory', function ($q) use (&$subCategories) {
            return $q->whereIn('report_sub_categories.name', $subCategories);
        });
    }

    public function scopeForPublishYear($query, $year)
    {
        return $query->whereYear('publish_date', $year);
    }

    public function scopeForPublishDate($query, $date)
    {
        return $query->where('publish_date', $date);
    }

    public function scopeForPublishMonth($query, $month)
    {
        // $monthNumber = 0;
        try {
            $monthNumber = Carbon::parse($month)->format("m");
        } catch (\Throwable $th) {
            return $query;
        }
        return $query->whereMonth('publish_date', $monthNumber);
    }

    public function scopeBetweenPublishDates($query, $fromDate, $toDate)
    {
        return $query->whereBetween('publish_date', [$fromDate, $toDate]);
    }

    public function scopeAttributeWithValue($query, string $attribute, string $value)
    {
        return $query->whereHas('filledAttributes', function ($q) use (&$attribute, &$value) {
            return $q->where('name', $attribute)->where('report_attribute_values.value', $value);
        });
    }

    public function scopeSearch($query, $searchTerm)
    {
        return $query
            ->where('reports.name', 'LIKE', "%$searchTerm%")
            ->orWhere('publish_date', 'LIKE', "%$searchTerm%")
            ->orWhereHas('subCategory', function ($q) use (&$searchTerm) {
                return $q->where('report_sub_categories.name', 'LIKE', "%$searchTerm%");
            })
            ->orWhereHas('category', function ($q) use (&$searchTerm) {
                return $q->where('report_categories.name', 'LIKE', "%$searchTerm%");
            })
            ->orWhereHas('filledAttributes', function ($q) use (&$searchTerm) {
                return $q
                    ->whereHas('type', function ($q) {
                        return $q->where('report_attribute_types.name', '!=', 'file');
                    })
                    ->where('report_attribute_values.value', 'LIKE', "%$searchTerm%");
            });
    }
}
