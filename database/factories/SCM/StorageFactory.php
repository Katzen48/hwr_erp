<?php

namespace Database\Factories\SCM;

use App\Models\SCM\Storage;
use Illuminate\Database\Eloquent\Factories\Factory;

class StorageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Storage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->word,
            'address' => $this->faker->streetAddress,
            'postcode' => $this->faker->postcode,
            'state' => $this->faker->state,
            'country' => $this->faker->country,
        ];
    }
}
