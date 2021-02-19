<?php

namespace App\Models\SCM;

use App\Models\GL\StorageEntry;
use App\Models\GL\ValueEntry;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Collection\Collection;

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
 *
 * @property Item $item
 * @property Collection|PurchaseLine $purchase_lines
 * @property Collection|SalesLine $sales_lines
 * @property Collection|StorageEntry $storage_entries
 * @property Collection|ValueEntry $value_entries
 */
class ItemVariant extends Model
{
    use HasFactory;

    public function item() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function purchase_lines() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurchaseLine::class);
    }

    public function sales_lines() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesLine::class);
    }

    public function storage_entries() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StorageEntry::class);
    }

    public function value_entries() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ValueEntry::class);
    }
}
