<?php

namespace App\Http\Resources\Administration;

use Illuminate\Http\Resources\Json\JsonResource;

class Employee extends JsonResource
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
            'outlet_id' => $this->outlet_id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'position' => $this->position,
            'purchaser' => $this->purchaser,
            'salesperson' => $this->salesperson,
        ];
    }
}
