<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentCategory extends Model
{
    use HasFactory, CreatedModifiedBy;

    protected $guarded = [];


    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */

    /**
     * getCreatedAtAttribute
     *
     * @param  mixed $attribute
     * @return mixed
     */
    public function getCreatedAtAttribute($attribute)
    {
        return $attribute ? Carbon::parse($attribute)->format(config('settings.createdat_datetime_format')) : '';
    }


    /**
     * ======================================================
     *                  Model Relations
     * ======================================================
     */

    /**
     * documents
     *
     * @return mixed
     */
    public function documents()
    {
        return $this->hasMany(Document::class, "category_id", "id");
    }

    /**
     * parent
     *
     * @return mixed
     */
    public function parent()
    {
        return $this->belongsTo(DocumentCategory::class, "parent_id", "id");
    }

    /**
     * children
     *
     * @return mixed
     */
    public function children()
    {
        return $this->hasMany(DocumentCategory::class, "parent_id", "id");
    }

    /**
     * author
     *
     * @param  mixed $value
     * @return mixed
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * ======================================================
     *                 Model Scope Queries
     * ======================================================
     */

    /**
     * parents
     *
     * @return mixed
     */
    public function scopeParents($query)
    {
        return $query->where('parent_id', '=', null);
    }
}
