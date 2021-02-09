<?php

namespace App\Models\SCM;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SalesHeader
 * @package App\Models\SCM
 *
 * @property integer $id
 * @property integer $employee_id
 * @property integer $outlet_id
 * @property integer $storage_id
 * @property float $order_amount
 * @property CarbonInterface $posting_date
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property CarbonInterface $archived_at
 */
class SalesHeader extends Model
{
    use HasFactory;

    protected $dates = ['posting_date','archived_at'];
}
