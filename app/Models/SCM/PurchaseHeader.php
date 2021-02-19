<?php

namespace App\Models\SCM;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PurchaseHeader
 * @package App\Models\SCM
 *
 * @property integer $id
 * @property integer $vendor_id
 * @property integer $employee_id
 * @property integer $outlet_id
 * @property integer $storage_id
 * @property CarbonInterface $delivery_date
 * @property CarbonInterface $posting_date
 * @property Float $purchase_amount
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property CarbonInterface $archived_at
 *
 * @property Collection|PurchaseLine $purchase_lines
 * @property Vendor $vendor
 */
class PurchaseHeader extends Model
{
    use HasFactory;

    protected $dates = ['delivery_date', 'posting_date', 'archived_at'];

    public function purchase_lines() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurchaseLine::class);
    }

    public function vendor() : \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->belongsTo(Vendor::class);
    }
}
