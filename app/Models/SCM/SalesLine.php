<?php

namespace App\Models\SCM;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class SalesLine
 * @package App\Models\SCM
 *
 * @property integer $sales_header_id
 * @property integer $line_no
 * @property integer $item_id
 * @property integer $item_variant_id
 * @property string $description
 * @property float $unit_price
 * @property float $vat_percent
 * @property float $vat_amount
 * @property integer $quantity
 * @property float $line_amount
 * @property integer $user_id
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property CarbonInterface $archived_at
 *
 * @property SalesHeader $salesHeader
 */
class SalesLine extends Model
{
    use HasFactory;

    protected $primaryKey = 'line_no';
    protected $dates = ['archived_at'];

    public function salesHeader() : BelongsTo
    {
        return $this->belongsTo(SalesHeader::class);
    }
}
