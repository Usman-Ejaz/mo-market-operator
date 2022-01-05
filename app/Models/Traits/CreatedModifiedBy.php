<?php 

namespace App\Models\Traits;

trait CreatedModifiedBy
{
    public static function bootCreatedModifiedBy()
    {
        // updating created_by when model is created
        static::creating(function ($model) {
            if (!$model->isDirty('created_by')) {
                $model->created_by = auth()->user()->id;
            }
        });

        // updating modified_by when model is updated
        static::updating(function ($model) {
            if (!$model->isDirty('modified_by')) {
                $model->modified_by = auth()->user()->id;
            }
        });
    }
}