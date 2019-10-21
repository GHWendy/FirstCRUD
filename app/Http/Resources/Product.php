<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $attributes = [
            'name' => $this->name,
            'price' => $this->price
        ];
        $links = [
            "self" => "http://firstcrud.test/api/products/".$this->id
        ];

        $data = [
            'type' => 'products',
            'id' => $this->id,
            'attributes' => $attributes,
            'links' => $links
        ];
        return $data;
    }
}
