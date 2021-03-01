<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\Item;
use App\Models\SCM\ItemVariant;
use App\Traits\DashboardVisible;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ItemVariantController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @param Item $item
     * @return Paginator
     */
    public function index(Item $item)
    {
        return \App\Http\Resources\SCM\ItemVariant::collection($item->item_variants()->simplePaginate(100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\SCM\ItemVariant
     */
    public function store(Item $item, Request $request)
    {
        $validated = $this->validate($request, [
            'description' => ['string', 'max:255'],
            'unit_price' => ['numeric'],
            'vat_percent' => ['numeric']
        ]);
        $validated = array_merge($validated, ["item_id" => $item->id]);

        $itemVariant = ItemVariant::query()->forceCreate($validated);

        $itemVariant = $itemVariant->refresh();
        return \App\Http\Resources\SCM\ItemVariant::make($itemVariant);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SCM\ItemVariant  $itemVariant
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item, ItemVariant $itemVariant)
    {
        return \App\Http\Resources\SCM\ItemVariant::make($itemVariant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Item $item
     * @param \App\Models\SCM\ItemVariant $itemVariant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item, ItemVariant $itemVariant)
    {
        $validated = $this->validate($request, [
            'item_id' => ['exists:items,id'],
            'description' => ['string', 'max:255'],
            'unit_price' => ['numeric'], // TODO , FLoat oder so?
            'vat_percent' => ['numeric'] // TODO . 0.00 .. 99.99 beschränken?
        ]);

        $itemVariant->forceFill($validated);
        $itemVariant->save();
        $itemVariant = $itemVariant->refresh();

        return \App\Http\Resources\SCM\ItemVariant::make($itemVariant);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\ItemVariant  $itemVariant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item, ItemVariant $itemVariant)
    {
        if(!$itemVariant->delete())
        {
            abort(500);
        }

        return Response::noContent();
    }

    public static function getDashboardParent()
    {
        return ItemController::class;
    }

    static function getDashboardId()
    {
        return 'item_variant';
    }

    public static function isEditable(): bool
    {
        return true;
    }

    static function getDashboardFields(): array
    {
        return [
            [
                'field' => 'id',
                'headerName' => 'ID', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
            [
                'field' => 'description',
                'headerName' => 'Beschreibung', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
            [
                'field' => 'unit_price',
                'headerName' => 'Preis', // TODO i18n
                'sortable' => true,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'vat_percent',
                'headerName' => 'MwSt. %', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
        ];
    }
}
