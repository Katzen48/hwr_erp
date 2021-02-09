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
 */
class PurchaseHeader extends Model
{
    use HasFactory;

    protected $dates = ['delivery_date', 'posting_date', 'archived_at'];
}
