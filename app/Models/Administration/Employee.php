<?php

namespace App\Models\Administration;

use App\Models\GL\StorageEntry;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Employee
 * @package App\Models\Administration
 *
 * @property integer $id
 * @property integer $outlet_id
 * @property string $first_name
 * @property string $last_name
 * @property string $position
 * @property boolean $purchaser
 * @property boolean $salesperson
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 *
 * @property \App\Models\SCM\Outlet $outlet;
 * @property Collection|\App\Models\SCM\PurchaseHeader $purchase_headers
 * @property Collection|\App\Models\SCM\SalesHeader $sales_header
 * @property Collection|StorageEntry $storage_entries
 * @property User $user
 */
class Employee extends Model
{
    use HasFactory;

    protected $casts = [
        'purchaser' => 'boolean',
        'salesperson' => 'boolean',
    ];

    public function outlet() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->BelongsTo(\App\Models\SCM\Outlet::class);
    }

    public function purchase_headers() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\SCM\PurchaseHeader::class, 'employee_id');
    }

    public function sales_headers() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(\App\Models\SCM\SalesHeader::class, 'employee_id');
    }

    public function storage_entries() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(StorageEntry::class, 'employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'employee_id');
    }
}
