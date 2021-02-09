<?php

namespace App\Models\GL;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class StorageEntry
 * @package App\Models\GL
 *
 * @property integer $entry_no
 * @property integer $storage_id
 * @property integer $item_id
 * @property integer $item_variant_id
 * @property string $item_description
 * @property string $item_variant_description
 * @property string $source_doc_type
 * @property integer $source_doc_id
 * @property integer $source_doc_line_no
 * @property integer $user_id
 * @property integer $employee_id
 * @property CarbonInterface $posting_date
 * @property CarbonInterface $delivery_date
 * @property integer $quantity
 * @property integer $applies_to_entry
 * @property integer $remaining_quantity
 * @property CarbonInterface $canceled_at
 * @property CarbonInterface $closed_at
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class StorageEntry extends Model
{
    use HasFactory;

    protected $dates = ['canceled_at', 'closed_at', 'posting_date', 'delivery_date'];
}
