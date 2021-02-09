<?php

namespace App\Models\SCM;

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
 */
class Outlet extends Model
{
    use HasFactory;
}
