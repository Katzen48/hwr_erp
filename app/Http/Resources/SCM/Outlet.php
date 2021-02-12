<?php

namespace App\Http\Resources\SCM;

use Illuminate\Http\Resources\Json\JsonResource;

class Outlet extends JsonResource
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
            'local_storage_id' => $this->local_storage_id,
            'shipping_storage_id' => $this->local_storage_id,
            'description' => $this->description,
            'address' => $this->address,
            'postcode' => $this->postcode,
            'state' => $this->state,
            'country' => $this->country,
        ];
    }
}
