<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
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
            'name' => $this->name,
            'description' => $this->description ?? '',
            'address' => $this->data->address,
            'banners' => EventBannerGalleryResource::collection($this->banners),
            'benchMapGallery' => EventBenchMapGalleryResource::collection($this->banchMaps()->orderBy('order', 'asc')->get()),
            'attachments' => EventAttachmentResource::collection($this->attachments()->orderBy('order', 'asc')->get())
        ];
    }
}
