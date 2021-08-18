<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class SupervisorAuthResource extends JsonResource
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
            'status'=>'success',
            'code' =>200,
            'message'=>'user logged in',
            'table'=>'supervisors',
            'data' => [
                'supervisor_name' => $this->getFullName(),
                'supervisor_email' => $this->supervisor_email,
                'supervisor_contact' => $this->supervisor_contact,
                'company'=> $this->company->only(['id','comp_name']),
                'supervisor_position' => $this->supervisor_position,
                'supervisor_gender'=> $this->supervisor_gender,
                'supervisor_link' => $this->supervisor_link,
                'supervisor_state' => $this->supervisor_state,
            ],
            'meta' => ['token'=>$this->getToken()]
        ];
    }
}