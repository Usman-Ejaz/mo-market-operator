<?php

namespace App\Models;

use App\Models\Traits\CreatedModifiedBy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaLibrary extends Model
{
    use HasFactory, CreatedModifiedBy;

    const MEDIA_STORAGE = 'medias/';

    protected $guarded = [];

    /**
     * ======================================================
     *                  Model Relations
     * ======================================================
     */

    /**
     * mediaFiles
     *
     * @return mixed
     */
    public function mediaFiles()
    {
        return $this->hasMany(MediaLibraryFile::class, 'media_library_id', 'id');
    }

    /**
     * ======================================================
     *                 Model Accessor Functions
     * ======================================================
     */

    /**
     * getCreatedAtAttribute
     *
     * @param  string $value
     * @return mixed
     */
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('settings.createdat_datetime_format')) : '';
    }

    /**
     * ======================================================
     *                  Model Scope Queries
     * ======================================================
     */

    /**
     * scopeTodaysPublishedRecords
     *
     * @param  mixed $query
     * @return mixed
     */
    public function scopeTodaysPublishedRecords($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    /**
     * ======================================================
     *                 Model Helper Functions
     * ======================================================
     */

    /**
     * files
     *
     * @return mixed
     */
    public function files()
    {
        $mediaFiles = $this->mediaFiles()->select("id", "file", "featured")->get();

        foreach ($mediaFiles as $media) {
            $media->file = serveFile(self::MEDIA_STORAGE . $this->directory . '/', $media->file);
        }

        return $mediaFiles;
    }
}
