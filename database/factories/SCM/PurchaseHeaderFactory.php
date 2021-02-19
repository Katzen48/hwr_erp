<?php

namespace Database\Factories\SCM;

use App\Models\Administration\Employee;
use App\Models\SCM\Outlet;
use App\Models\SCM\PurchaseHeader;
use App\Models\SCM\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseHeaderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PurchaseHeader::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $outlet = Outlet::query()->first() ?: Outlet::factory(1)->create();

        return [
            'vendor_id' => Vendor::query()->first()->id,
            'employee_id' => Employee::query()->first()->id,
            'outlet_id' => $outlet->id,
            'storage_id' => $outlet->local_storage->id,
            'delivery_date' => Carbon::now()->addDays(2)->setHour(0)->setMinute(0)->setSecond(0),
            'posting_date' => Carbon::now(),
            'purchase_amount' => 0,
            'archived_at' => null,
        ];
    }
}
