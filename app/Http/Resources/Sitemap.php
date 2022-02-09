<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Sitemap extends JsonResource
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
            'id' => $this->id,
            'url' => url('/pages/'.$this->slug),
            'images' => substr_count($this->description, '<img'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i P'),
        ];

        //return parent::toArray($request);
    }
}
