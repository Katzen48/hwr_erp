<?php

namespace App\Models\SCM;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ItemVariant
 * @package App\Models\SCM
 *
 * @property integer $id
 * @property integer $item_id
 * @property string $description
 * @property float $unit_price
 * @property float $vat_percent
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property Item $item
 */
class ItemVariant extends Model
{
    use HasFactory;

    public function item() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
