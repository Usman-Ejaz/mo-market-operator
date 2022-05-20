<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SiteSearchResource extends JsonResource
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
            'title' => $this->title ?: $this->question,
            'slug' => $this->slug ?: null,
            'description' => $this->description ?: $this->answer ?: '',
            'keywords' => $this->keywords ?: $this->location ?: '',
            'category' => $this->category_id ? $this->category->name : '',
        ];
    }
}
