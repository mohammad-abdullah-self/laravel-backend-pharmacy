<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Blog extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $published = explode(' ', $this->published_at);
        return [
            'id' => $this->id,
            'picture' => $this->picture,
            'title' => $this->title,
            'slug' => $this->slug,
            'body' => $this->body,
            'published_at' => $this->published_at,
            'published_date' => $published[0],
            'published_time' =>  $published[1],
        ];
    }
}
