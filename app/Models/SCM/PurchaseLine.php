<?php

namespace App\Models\SCM;

use App\Models\GL\StorageEntry;
use App\Models\GL\ValueEntry;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Ramsey\Collection\Collection;

/**
 * Class PurchaseLine
 * @package App\Models\SCM
 *
 * @property integer $purchase_header_id
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
 * @property PurchaseHeader $purchaseHeader
 * @property Item $item
 * @property ItemVariant $item_variant
 * @property Collection|StorageEntry $storage_entries
 * @property Collection|ValueEntry $value_entries
 */
class PurchaseLine extends Model
{
    use HasFactory;

    protected $primaryKey = 'line_no';
    protected $dates = ['archived_at'];

    public function purchase_header() : BelongsTo
    {
        return $this->belongsTo(PurchaseHeader::class);
    }

    public function item() : BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function item_variant() : BelongsTo
    {
        return $this->belongsTo(ItemVariant::class);
    }

    public function storage_entries() : HasMany
    {
        return $this->hasMany(StorageEntry::class, 'source_doc_line_no');
    }

    public function value_entries() : HasMany
    {
        return $this->hasMany(ValueEntry::class, 'source_doc_line_no');
    }
}
