<?php

namespace App\Http\Resources;

use App\Models\Publisher;
use Illuminate\Http\Resources\Json\JsonResource;

class PublisherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $image = $this->uploads()->wherePivot('alias_category', Publisher::FILE_CATEGORY_LOGO)->first();

        return [
            'name' => $this->name,
            'description' => $this->description ?? '',
            'url' => $this->url,
            'logo' => asset('storage/' . @$image['server_file'])
        ];
    }
}
