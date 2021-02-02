<?php

namespace App\Models\SCM;

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
 * @property \DateTimeImmutable $created_at
 * @property \DateTimeImmutable $updated_at
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
