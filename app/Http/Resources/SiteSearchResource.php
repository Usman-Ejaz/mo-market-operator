<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

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
        $linkPrefix = $this->getLinkProfixForModule($this);

        return [
            'title' => $this->title ?: $this->question,
            'slug' => $this->slug ? ($linkPrefix . $this->slug) : null,
            'description' => $this->description ?: $this->answer ?: '',
            'keywords' => $this->keywords ?: $this->location ?: '',
            'category' => $this->category_id ? $this->category->name : '',
        ];
    }


    private function getLinkProfixForModule()
    {
        $linkPrefix = Str::plural(strtolower(get_class($this->resource)));
        $linkPrefix = str_replace("app\\models\\", "", $linkPrefix);

        if ($linkPrefix === "posts") {
            $linkPrefix = Str::plural(strtolower($this->post_category));
        }

        if ($linkPrefix === "pages") {
            return "/";
        }

        return '/' . $linkPrefix . '/';
    }
}
