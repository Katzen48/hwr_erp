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
 */
class Employee extends Model
{
    use HasFactory;

    protected $casts = [
        'purchaser' => 'boolean',
        'salesperson' => 'boolean',
    ];
}
