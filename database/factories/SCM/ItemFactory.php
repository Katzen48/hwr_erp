<?php

namespace Database\Factories\SCM;

use App\Models\SCM\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Item::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'description' => $this->faker->word,
            'storage_posting_method' => $this->faker->randomElement(['FIFO', 'LIFO']),
        ];
    }
}
