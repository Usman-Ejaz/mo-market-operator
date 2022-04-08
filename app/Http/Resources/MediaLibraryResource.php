<?php

namespace App\Http\Resources;

use App\Models\MediaLibrary;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaLibraryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $mediaLibrary = $this->mediaLibrary;
        
        $filePath = serveFile(MediaLibrary::MEDIA_STORAGE . $mediaLibrary->directory . '/', $this->file);

        return [
            'title' => $mediaLibrary->name,
            'description' => $mediaLibrary->description,
            'image' => $filePath
        ];
    }
}
