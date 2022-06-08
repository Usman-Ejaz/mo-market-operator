<?php 

namespace App\Models\Traits;

use App\Events\ActivityLogEvent;
use App\Models\ActivityLog;

trait CreatedModifiedBy
{
    public static function bootCreatedModifiedBy()
    {
        // updating created_by when model is created
        static::creating(function ($model) {
            if (! $model->isDirty('created_by')) {
                $model->created_by = auth()->user()->id;                
            }
        });

        static::created(function ($model) {
            event(new ActivityLogEvent("was just created.", $model, "create", auth()->id()));
        });

        // updating modified_by when model is updated
        static::updating(function ($model) {
            if (! $model->isDirty('modified_by') && auth()->check()) {
                $model->modified_by = auth()->user()->id;
                event(new ActivityLogEvent("was just updated.", $model, "update", auth()->id()));
            }
        });

        static::deleting(function ($model) {
            event(new ActivityLogEvent("was just deleted.", $model, "delete", auth()->id()));
        });
    }
}