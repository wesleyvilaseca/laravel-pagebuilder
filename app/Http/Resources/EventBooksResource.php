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
        if()
        return [
            'name' => $this->name,
            'subject' => $this->subject ?? '',
            'isbn' => $this->isbn ?? '',
            'price' => $this->price ? 'R$ ' . Utils::numberFormat($this->price) : '',
            'price_discount' => $this->price_discount ? 'R$ ' . Utils::numberFormat($this->price_discount) : '',
            'link' => $this->link 
                ? (str_starts_with($this->link, 'http://') || str_starts_with($this->link, 'https://') 
                    ? $this->link 
                    : 'https://' . $this->link) 
                    : '',            
            'authors' => $this->author,
            'publisher' => $this->publishers[0]->name ?? ''
            // 'url' => $this->url ?? '',
            // 'authors' => AuthorResource::collection($this->authors)
        ];
    }
}
