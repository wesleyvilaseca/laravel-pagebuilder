<?php

namespace App\Http\Resources;

use App\Models\EventBenchMapGallery;
use App\Models\EventSchedule;
use Illuminate\Http\Resources\Json\JsonResource;

class EventScheduleGalleryResource extends JsonResource
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
        $image = $images->where('pivot.alias_category', EventSchedule::FILE_CATEGORY_SCHEDULE)->first();

        return [
            'name' => $this->name,
            'description' => $this->description ?? '',
            'link' => $this->link ?? '',
            'image' =>  asset('storage/' . @$image['server_file'])
        ];
    }
}
