<?php

namespace App\Http\Resources;

use App\Models\EventAttachment;
use App\Models\EventBenchMapGallery;
use Illuminate\Http\Resources\Json\JsonResource;

class EventAttachmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        $files =  $this->uploads;
        $file = $files->where('pivot.alias_category', EventAttachment::FILE_CATEGORY_ATTACHMENT)->first();

        return [
            'name' => $this->name,
            'description' => $this->description ?? '',
            'link' =>  asset('storage/' . @$file['server_file'])
        ];
    }
}
