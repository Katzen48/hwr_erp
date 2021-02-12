<?php

namespace App\Http\Resources\SCM;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseLine extends JsonResource
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
            'line_no' => $this->line_no,
            'item_id' => $this->item_id,
            'item_variant_id' => $this->item_variant_id,
            'description' => $this->description,
            'unit_price' => $this->unit_price,
            'vat_percent' => $this->vat_percent,
            'vat_amount' => $this->vat_amount,
            'quantity' => $this->quantity,
            'line_amount' => $this->line_amount,
            'archived_at' => $this->archived_at,
        ];
    }
}
