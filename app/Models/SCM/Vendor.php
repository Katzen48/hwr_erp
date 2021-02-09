<?php

namespace App\Models\SCM;

use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Vendor
 * @package App\Models\SCM
 *
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $postcode
 * @property string $state
 * @property string $country
 * @property CarbonInterface $created_at
 * @property CarbonInterface $updated_at
 */
class Vendor extends Model
{
    use HasFactory;
}
