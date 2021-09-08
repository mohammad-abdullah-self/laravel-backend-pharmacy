<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
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
            'name' => $this->name,
            'phone' => $this->userdetail->phone,
            'email' => $this->email,
            'address' => $this->userdetail->address,
            'avatar' => $this->userdetail->avatar,
            'email_verified_at' => $this->email_verified_at ? $this->email_verified_at : false,
            'created_at' => $this->created_at->format('d-m-y h:i a'),
            'role' => $this->getUserRoleName(),
            'notificationCount' => auth()->user()->unreadNotifications->count(),
            // 'updated_at' => $this->updated_at,
        ];
    }
}
