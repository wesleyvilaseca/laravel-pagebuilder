<?php

namespace App\Http\Resources;

use App\Supports\Helper\Utils;
use Illuminate\Http\Resources\Json\JsonResource;

class EventBooksResource extends JsonResource
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
            'subject' => $this->subject ?? '',
            'isbn' => $this->isbn ?? '',
            'price' => Utils::numberFormat($this->price) ?? '',
            'presential_discount' => $this->presential_discount ? $this->presential_discount . '%' : '',
            'virtual_discount' => $this->virtual_discount ? $this->virtual_discount . '%' : '',
            'link' => $this->link ?? '',
            // 'url' => $this->url ?? '',
            'authors' => AuthorResource::collection($this->authors)
        ];
    }
}
