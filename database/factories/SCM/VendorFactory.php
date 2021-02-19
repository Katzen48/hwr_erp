<?php

namespace Database\Factories\SCM;

use App\Models\SCM\Vendor;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Vendor::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company,
            'address' => $this->faker->streetAddress,
            'postcode' => $this->faker->postcode,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
        ];
    }
}
