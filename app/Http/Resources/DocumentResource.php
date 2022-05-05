<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DocumentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'title' => $this->title,
            'files' => $this->document_links,
            'thumbnail' => $this->image,
            'filenames' => $this->file,
            'keywords' => $this->keywords,
            'slug' => $this->slug,
            'category' => $this->category->slug,
            'date' => $this->created_at
        ];
    }
}
