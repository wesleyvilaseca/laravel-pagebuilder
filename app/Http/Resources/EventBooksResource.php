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
            'price_discount' => $this->price_discount ? $this->price_discount . '%' : '',
            'link' => $this->link ?? '',
            'authors' => $this->author
            // 'url' => $this->url ?? '',
            // 'authors' => AuthorResource::collection($this->authors)
        ];
    }
}
