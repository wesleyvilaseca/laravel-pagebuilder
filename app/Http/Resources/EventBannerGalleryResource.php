<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventBannerGalleryResource extends JsonResource
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
        $imageDesktop = $images->where('pivot.alias_category', 'event-banner-gallery-desktop')->first();
        $imageMobile = $images->where('pivot.alias_category', 'event-banner-gallery-mobile')->first() ?? $imageDesktop;

        return [
            'name' => $this->name,
            'description' => $this->description ?? '',
            'link' => $this->link ?? '',
            'image_desktop' =>  asset('storage/' . @$imageDesktop['server_file']) ,
            'image_mobile' => asset('storage/' . @$imageMobile['server_file'])
        ];
    }
}
