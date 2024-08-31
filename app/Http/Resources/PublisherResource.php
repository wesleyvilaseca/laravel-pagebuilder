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
        $priceList = $this->uploads()->wherePivot('alias_category', Publisher::FILE_CATEGORY_PUBLISHER_BOOK_PRICE_LIST)->first();

        return [
            'name' => $this->name,
            'description' => $this->description ?? '',
            'url' => $this->url,
            'logo' => @$image ? asset('storage/' . @$image['server_file']) : '',
            'price_list' => @$priceList ? asset('storage/' . $priceList['server_file']) : '',
            'email' => $this->email,
            'site' => $this->site,
            'address' => $this->data?->address ?? '',
            'social' => $this->data?->social ?? '',
        ];
    }
}
