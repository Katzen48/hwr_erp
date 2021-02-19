<?php

namespace Database\Factories\SCM;

use App\Models\SCM\Outlet;
use App\Models\SCM\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

class OutletFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Outlet::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $localStorage = Storage::query()->first() ?: Storage::factory(1)->create();
        $shippingStorage = Storage::query()->whereNotIn('id', [$localStorage->id])->first() ?: Storage::factory(1)->create();

        return [
            'local_storage_id' => $localStorage->id,
            'shipping_storage_id' => $shippingStorage->id,
            'description' => $this->faker->word,
            'address' => $this->faker->streetAddress,
            'postcode' => $this->faker->postcode,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
        ];
    }
}
