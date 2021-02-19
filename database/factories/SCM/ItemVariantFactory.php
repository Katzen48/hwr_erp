<?php

namespace Database\Factories\SCM;

use App\Models\SCM\Item;
use App\Models\SCM\ItemVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemVariantFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ItemVariant::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $item = Item::query()->first() ?: Item::factory(1)->create();

        return [
            'item_id' => $item->id,
            'description' => $item->description . ' ' . $this->faker->word,
            'unit_price' => $this->faker->randomFloat(2, 0, 1000),
            'vat_percent' => 19,
        ];
    }
}
