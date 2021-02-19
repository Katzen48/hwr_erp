<?php

namespace Database\Factories\SCM;

use App\Models\SCM\Item;
use App\Models\SCM\PurchaseHeader;
use App\Models\SCM\PurchaseLine;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseLineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PurchaseLine::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $purchaseHeader = PurchaseHeader::query()->first() ?: PurchaseHeader::factory(1)->create();
        $item = Item::query()->first() ?: Item::factory(1)->create();
        $itemVariant = $item->item_variants()->first();

        $latestLine =

        $vatAmount = $itemVariant->unit_price * ($itemVariant->vat_percent / 100);

        return [
            'purchase_header_id' => $purchaseHeader->id,
            'line_no' => ($purchaseHeader->purchase_lines()->latest()->first()->line_no ?? 0) + ($this->faker->randomFloat(5, 0, 1) * 10000),
            'item_id' => $item->id,
            'item_variant_id' => $itemVariant->id,
            'description' => $itemVariant->description,
            'unit_price' => $itemVariant->unit_price,
            'vat_percent' => $itemVariant->vat_percent,
            'vat_amount' => $vatAmount,
            'quantity' => 1,
            'line_amount' => $itemVariant->unit_price + $vatAmount,
            'user_id' => User::query()->first()->id,
            'archived_at' => null,
        ];
    }
}
