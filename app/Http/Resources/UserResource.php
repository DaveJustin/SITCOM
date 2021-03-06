<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'user_id' => $this->id,
            'name'=> $this->getFullName(),
            'email'=> $this->email,
            'role' => $this->userRole(),
            'avatar' => $this->userImage()
        ];
    }
}
