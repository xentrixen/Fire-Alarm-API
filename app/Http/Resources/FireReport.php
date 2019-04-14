<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class FireReport extends JsonResource
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
        $resource["level_of_fire"] = $this->level_of_fire != null ? $this->level_of_fire : '-';
        $resource["reporter_name"] = $this->citizen->name;
        $resource["reporter_email"] = $this->citizen->email;
        return $resource;
    }
}
