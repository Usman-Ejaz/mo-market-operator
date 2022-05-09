<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MediaLibrary extends Model
{
    use HasFactory;

    const MEDIA_STORAGE = 'medias/';

    protected $guarded = [];


    public function mediaFiles()
    {
        return $this->hasMany(MediaLibraryFile::class, 'media_library_id', 'id');
    }
    
    /**
     * files
     *
     * @return mixed
     */
    public function files()
    {
        $mediaFiles = $this->mediaFiles()->select( "file", "featured")->get();

        foreach ($mediaFiles as $media) {
            $media->file = serveFile(self::MEDIA_STORAGE . $this->directory . '/', $media->file);
        }

        return $mediaFiles;
    }
    
    /**
     * getCreatedAtAttribute
     *
     * @param  string $value
     * @return void
     */
    public function getCreatedAtAttribute($value)
    {
        return $value ? Carbon::parse($value)->format(config('settings.datetime_format')) : '';
    }
}