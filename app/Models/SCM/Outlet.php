<?php

namespace App\Models\SCM;

use App\Models\Administration\Employee;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Outlet
 * @package App\Models\SCM
 *
 * @property integer $id
 * @property integer $local_storage_id
 * @property integer $shipping_storage_id
 * @property string $description
 * @property string $address
 * @property string $postcode
 * @property string $state
 * @property string $country
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 *
 * @property Storage $local_storage
 * @property Storage $global_storage
 * @property Collection|Employee $employees
 */
class Outlet extends Model
{
    use HasFactory;

    public function local_storage() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Storage::class, 'local_storage_id');
    }

    public function shipping_storage() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Storage::class, 'shipping_storage_id');
    }

    public function employee() : \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
