<?php

namespace App\Http\Resources\SCM;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseHeader extends JsonResource
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
            'vendor_id' => $this->vendor_id,
            'employee_id' => $this->employee_id,
            'outlet_id' => $this->outlet_id,
            'storage_id' => $this->storage_id,
            'delivery_date' => $this->delivery_date ? $this->delivery_date->toDateString() : null,
            'posting_date' => $this->posting_date->toDateString() ? $this->posting_date->toDateString() : null,
            'purchase_amount' => $this->purchase_amount,
            'archived_at' => $this->archived_at,
        ];
    }
}
