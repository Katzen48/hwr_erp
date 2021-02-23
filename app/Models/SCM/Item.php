<?php

namespace App\Models\SCM;

use App\Models\GL\StorageEntry;
use App\Models\GL\ValueEntry;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Item
 * @package App\Models\SCM
 *
 * @property integer $id
 * @property string $description
 * @property string $storage_posting_method // FIFO or LIFO
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 *
 * @property Collection|ItemVariant $item_variants
 * @property Collection|PurchaseLine $purchase_lines
 * @property Collection|SalesLine $sales_lines
 * @property Collection|StorageEntry $storage_entries
 * @property Collection|ValueEntry $value_entries
 */
class Item extends Model
{
    use HasFactory;

    public function item_variants() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ItemVariant::class);
    }

    public function purchase_lines() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurchaseLine::class);
    }

    public function sales_lines() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesLine::class);
    }

    public function storage_entries() : \Illuminate\DataBase\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StorageEntry::class);
    }

    public function value_entries() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ValueEntry::class);
    }
}
