<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserFeedback extends JsonResource
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
            'name' => $this->user->name,
            'avatar' => $this->user->userdetail->avatar,
            'stars' => $this->stars,
            'comment' => $this->comment,
            'published' => $this->published === 1 ? true : false,
            'created_at' => $this->created_at->format('d-m-y h:i a'),

        ];
    }
}
