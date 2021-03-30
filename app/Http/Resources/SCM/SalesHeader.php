<?php

namespace App\Http\Resources\SCM;

use Illuminate\Http\Resources\Json\JsonResource;

class SalesHeader extends JsonResource
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
            'employee_id' => $this->employee_id,
            'outlet_id' => $this->outlet_id,
            'storage_id' => $this->storage_id,
            'posting_date' => $this->posting_date ? $this->posting_date->toDateString() : null,
            'order_amount' => $this->order_amount,
            'archived_at' => $this->archived_at,
        ];
    }
}
