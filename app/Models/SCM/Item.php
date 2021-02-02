<?php

namespace App\Models\SCM;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 * @package App\Models\SCM
 *
 * @property integer $id
 * @property string $description
 * @property \DateTimeImmutable $created_at
 * @property \DateTimeImmutable $updated_at
 * @property Collection|ItemVariant $item_variants
 */
class Item extends Model
{
    use HasFactory;

    public function item_variants() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ItemVariant::class);
    }
}
