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
        return [
            'id' => $this->id,
            'category' => $this->category->name,
            'picture' => $this->picture,
            'name' => $this->name,
            'generic' => $this->generic,
            'type' => $this->type,
            'manufactured' => $this->manufactured,
            'size' => $this->size,
            'quantity' => $this->quantity,
            'pieces_per_pata' => $this->pieces_per_pata,
            'dose' => $this->dose,
            'old_mrp' => $this->old_mrp,
            'mrp' => $this->mrp,
        ];
    }
}
