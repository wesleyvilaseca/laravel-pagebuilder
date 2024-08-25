<?php

namespace App\Http\Resources;

use App\Models\EventBenchMapGallery;
use Illuminate\Http\Resources\Json\JsonResource;

class EventBenchMapGalleryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $images =  $this->uploads;
        $image = $images->where('pivot.alias_category', EventBenchMapGallery::FILE_CATEGORY_BENCHMAP)->first();

        return [
            'name' => $this->name,
            'description' => $this->description ?? '',
            'link' => $this->link ?? '',
            'image' =>  asset('storage/' . @$image['server_file'])
        ];
    }
}
