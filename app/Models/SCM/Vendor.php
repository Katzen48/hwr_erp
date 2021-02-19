<?php

namespace App\Models\SCM;

use App\Models\GL\ValueEntry;
use Carbon\CarbonInterface;
use Dotenv\Parser\Value;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Ramsey\Collection\Collection;

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
 *
 * @property Collection|PurchaseHeader $purchase_headers
 * @property Collection|ValueEntry $value_entries
 */
class Vendor extends Model
{
    use HasFactory;

    public function purchase_headers() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurchaseHeader::class);
    }

    public function value_entries() : \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(ValueEntry::class);
    }
}
