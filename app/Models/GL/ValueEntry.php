<?php

namespace App\Models\GL;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ValueEntry
 * @package App\Models\GL
 *
 * @property integer $entry_no
 * @property integer $outlet_id
 * @property integer $item_id
 * @property integer $item_variant_id
 * @property string $item_description
 * @property string $item_variant_description
 * @property string $source_doc_type
 * @property int $source_doc_id
 * @property int $source_doc_line_no
 * @property int $user_id
 * @property int $employee_id
 * @property CarbonInterface $posting_date
 * @property float $unit_price
 * @property float $vat_percent
 * @property float $vat_amount
 * @property float $line_amount
 * @property int $applies_to_entry
 * @property int $vendor_id
 * @property CarbonInterface $canceled_at
 * @property CarbonInterface $closed_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class ValueEntry extends Model
{
    use HasFactory;

    protected $primaryKey = 'entry_no';
    protected $dates = ['posting_date', 'cancelled_at', 'closed_at'];
}

