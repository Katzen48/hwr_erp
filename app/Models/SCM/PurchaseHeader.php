<?php

namespace App\Models\SCM;

use App\Models\Administration\Employee;
use App\Models\GL\StorageEntry;
use App\Models\GL\ValueEntry;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Collection\Collection;

/**
 * Class PurchaseHeader
 * @package App\Models\SCM
 *
 * @property integer $id
 * @property integer $vendor_id
 * @property integer $employee_id
 * @property integer $outlet_id
 * @property integer $storage_id
 * @property CarbonInterface $delivery_date
 * @property CarbonInterface $posting_date
 * @property Float $purchase_amount
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property CarbonInterface $archived_at
 *
 * @property Collection|PurchaseLine $purchase_lines
 * @property Collection|PurchaseLine $open_purchase_lines
 * @property Vendor $vendor
 * @property Employee $employee
 * @property Outlet $outlet
 * @property Storage $storage
 * @property Collection|StorageEntry $storage_entries
 * @property Collection|ValueEntry $value_entries
 */
class PurchaseHeader extends Model
{
    use HasFactory;

    protected $dates = ['delivery_date', 'posting_date', 'archived_at'];

    public function purchase_lines() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurchaseLine::class);
    }

    public function open_purchase_lines() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->purchase_lines()->where('archived_at', '=', null);
    }

    public function vendor() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Vendor::class);
    }

    public function employee() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function outlet() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Outlet::class);
    }

    public function storage() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Storage::class);
    }

    public function storage_entries() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StorageEntry::class, 'source_doc_id');
    }

    public function value_entries() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ValueEntry::class, 'source_doc_id');
    }

    public function getNextLineNo($multiplier = 1) : int
    {
        return ($this->purchase_lines()->latest('line_no')->line_no ?? 0) + 100 * $multiplier;
    }
}
