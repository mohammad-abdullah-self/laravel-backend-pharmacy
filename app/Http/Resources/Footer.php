<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Footer extends JsonResource
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
            'logo' => $this->logo,
            'name' => $this->name,
            'description' => $this->description,
            'f_link' => $this->f_link,
            't_link' => $this->t_link,
            'y_link' => $this->y_link,
            'phone' => $this->phone,
            'houre' => $this->houre,
            'email' => $this->email,
            'address' => $this->address,
        ];
    }
}
