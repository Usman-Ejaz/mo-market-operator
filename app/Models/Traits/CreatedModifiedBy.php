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
                event(new ActivityLogEvent($model->getLoggableArray("was just created.", $model, "create", auth()->id())));
            }
        });

        // updating modified_by when model is updated
        static::updating(function ($model) {
            if (! $model->isDirty('modified_by') && auth()->check()) {
                $model->modified_by = auth()->user()->id;
            }
        });

        static::updated(function ($model) {
            event(new ActivityLogEvent($model->getLoggableArray("was just updated.", $model, "update", auth()->id())));
        });

        static::deleting(function ($model) {
            event(new ActivityLogEvent($model->getLoggableArray("was just deleted.", $model, "delete", auth()->id())));
        });
    }

    public function getLoggableArray($message, $model, $type, $userId)
    {
        $module = str_replace("App\\Models\\", "", get_class($model));

        if ($type === "create") {
            $message = "New " . strtolower($module) . ' ' . $message;
        } else {
            $message = $module . ' ' . $message;
        }

        return [
            'message' => $message,
            'type' => $type,
            'model' => get_class($model),
            'module' => $module,
            'done_by' => $userId,
            'new' => $model ? $model->toJson() : null,
            'old' => $model ? json_encode($model->getChanges()) : null
        ];
    }
}
