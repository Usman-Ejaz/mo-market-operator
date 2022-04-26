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
            'filenames' => $this->file,
            'keywords' => $this->keywords,
            'category' => $this->category->name,
            'date' => $this->created_at
        ];
    }
}
