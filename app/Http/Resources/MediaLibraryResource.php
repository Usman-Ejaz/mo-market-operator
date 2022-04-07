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
        $featuredImage = $this->mediaFiles;
        
        $filePath = serveFile(MediaLibrary::MEDIA_STORAGE . $this->directory . '/', $featuredImage->file);

        return [
            'title' => $this->name,
            'description' => $this->description,
            'image' => $filePath
        ];
    }
}
