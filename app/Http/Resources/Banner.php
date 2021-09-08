<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Banner extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'picture' => $this->picture,
            'created_at' => $this->created_at->format('d-m-y h:i a'),
        ];
    }
}
