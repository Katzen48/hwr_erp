<?php

namespace App\Models\SCM;

use App\Models\GL\StorageEntry;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Storage
 * @package App\Models\SCM
 *
 * @property integer $id
 * @property string $description
 * @property string $address
 * @property string $postcode
 * @property string $state
 * @property string $country
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 *
 * @property Collection|Outlet $outlets
 * @property Collection|PurchaseHeader $purchase_headers
 * @property Collection|SalesHeader $sales_headers
 * @property Collection|StorageEntry $storage_entries
 */
class Storage extends Model
{
    use HasFactory;

    public function outlets() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Outlet::class);
    }

    public function purchase_headers() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurchaseHeader::class, 'storage_id');
    }

    public function sales_headers() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesHeader::class, 'storage_id');
    }

    public function storage_entries() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StorageEntry::class, 'storage_id');
    }
}
