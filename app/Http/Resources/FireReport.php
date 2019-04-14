<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Citizen extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resource = parent::toArray($request);
        $resource["reporter_name"] = $this->citizen->name;
        $resource["reporter_email"] = $this->citizen->email;
        return $resource;
    }
}
