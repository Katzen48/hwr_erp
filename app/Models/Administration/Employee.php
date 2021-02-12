<?php

namespace App\Models\Administration;

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
 * @property Outlet $outlet;
 * @property Collection|PurchaseHeader $purchase_headers
 * @property Collection|SalesHeader $sales_header
 */
class Employee extends Model
{
    use HasFactory;

    protected $casts = [
        'purchaser' => 'boolean',
        'salesperson' => 'boolean',
    ];

    public function outlet() : \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Outlet::class);
    }

    public function purchase_headers() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurchaseHeader::class, 'employee_id');
    }

    public function sales_headers() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesHeader::class, 'employee_id');
    }
}
