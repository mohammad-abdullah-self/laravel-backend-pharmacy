<?php

namespace App\Http\Resources;

use App\ContactFile;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ContactFile as ContactFileResource;

class Contact extends JsonResource
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
            'email' => $this->email,
            'phone' => $this->phone,
            'subject' => $this->subject,
            'comment' => $this->comment,
            'file' => new ContactFileResource(ContactFile::where('contact_id', $this->id)->first()),
            'created_at' => $this->created_at->format('d-m-y h:i a'),
        ];
    }
}
