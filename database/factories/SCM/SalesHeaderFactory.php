<?php

namespace Database\Factories\SCM;

use App\Models\Administration\Employee;
use App\Models\SCM\Outlet;
use App\Models\SCM\SalesHeader;
use App\Models\SCM\Vendor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class SalesHeaderFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SalesHeader::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $outlet = Outlet::query()->first() ?: Outlet::factory(1)->create();

        return [
            'employee_id' => Employee::query()->first()->id,
            'outlet_id' => $outlet->id,
            'storage_id' => $outlet->local_storage->id,
            'posting_date' => Carbon::now(),
            'order_amount' => 0,
            'archived_at' => null,
        ];
    }
}
