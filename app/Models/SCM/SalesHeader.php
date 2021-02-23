<?php

namespace App\Models\SCM;

use App\Http\Controllers\API\SCM\OutletController;
use App\Models\Administration\Employee;
use App\Models\GL\StorageEntry;
use App\Models\GL\ValueEntry;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Collection\Collection;

/**
 * Class SalesHeader
 * @package App\Models\SCM
 *
 * @property integer $id
 * @property integer $employee_id
 * @property integer $outlet_id
 * @property integer $storage_id
 * @property float $order_amount
 * @property CarbonInterface $posting_date
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 * @property CarbonInterface $archived_at
 *
 * @property Collection|SalesLine $sales_lines
 * @property Collection|SalesLine $open_sales_lines
 * @property Employee $employee
 * @property Outlet $outlet
 * @property Storage $storage
 * @property Collection|StorageEntry $storage_entries
 * @property Collection|ValueEntry $value_entries
 */
class SalesHeader extends Model
{
    use HasFactory;

    protected $dates = ['posting_date','archived_at'];

    public function sales_lines() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SalesLine::class);
    }

    public function open_sales_lines() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->sales_lines()->where('archived_at', '=', null);
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
        return ($this->sales_lines()->latest('line_no')->line_no ?? 0) + 100 * $multiplier;
    }
}
