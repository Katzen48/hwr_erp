<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\PurchaseHeader;
use App\Models\SCM\PurchaseLine;
use App\Traits\DashboardVisible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class PurchaseLineController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function index(PurchaseHeader $purchaseHeader)
    {
        return \App\Http\Resources\SCM\PurchaseLine::collection($purchaseHeader->purchase_lines()->simplePaginate(100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param PurchaseHeader $purchaseHeader
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\SCM\PurchaseLine
     */
    public function store(PurchaseHeader $purchaseHeader, Request $request)
    {
        $validated = $this->validate($request, [
            'item_id' => ['nullable', 'exists:items,id'],
            'item_variant_id' => ['nullable', 'exists:item_variants,id'],
            'description' => ['string', 'max:255'],
            'unit_price' => ['numeric'],
            'vat_percent' => ['numeric'],
            'vat_amount' => ['numeric'],
            'quantity' => ['numeric'],
            'line_amount' =>['numeric']
        ]);
        $validated = array_merge($validated, ['purchase_header_id' => $purchaseHeader->id, 'line_no' => $purchaseHeader->getNextLineNo()]);

        $purchaseLine = new PurchaseLine();
        $purchaseLine->forceFill($validated);
        $purchaseLine->user_id = auth()->user()->id ?? 1; // TODO
        $this->onValidate($purchaseLine);

        $purchaseLine->save();
        //$purchaseLine = $purchaseLine->refresh();
        return \App\Http\Resources\SCM\PurchaseLine::make($purchaseLine);
    }

    public function onValidate(PurchaseLine $purchaseLine)
    {
        if($purchaseLine->isDirty('item_id'))
        {
            if (!$purchaseLine->item_variant_id)
            {
                if (!$purchaseLine->item_id)
                {
                    $purchaseLine->item_variant_id = null;
                }
                else
                {
                    if (!$purchaseLine->item->item_variants()->find($purchaseLine->item_variant_id))
                    {
                        $purchaseLine->item_variant_id = null;
                    }
                }
            }
        }

        if($purchaseLine->isDirty('item_variant_id'))
        {
            if($purchaseLine->item_variant_id) {
                $purchaseLine->description = $purchaseLine->item_variant->description;
                $purchaseLine->unit_price = $purchaseLine->item_variant->unit_price;
                $purchaseLine->vat_percent = $purchaseLine->item_variant->vat_percent;
            }
            else
            {
                $purchaseLine->description = "";
                $purchaseLine->unit_price = 0;
                $purchaseLine->vat_percent = 0;
            }
        }

        if($purchaseLine->isDirty('unit_price') && $purchaseLine->unit_price)
        {
            $this->calcAmounts($purchaseLine);
        }

        if($purchaseLine->isDirty('vat_percent') && $purchaseLine->vat_percent)
        {
            $this->calcAmounts($purchaseLine);
        }

        if($purchaseLine->isDirty('quantity') && $purchaseLine->quantity)
        {
            $this->calcAmounts($purchaseLine);
        }
    }

    public function calcAmounts(PurchaseLine $purchaseLine)
    {
        $purchaseLine->vat_amount = $purchaseLine->quantity * $purchaseLine->unit_price / (100 + $purchaseLine->vat_percent) * $purchaseLine->vat_percent;
        $purchaseLine->line_amount = $purchaseLine->quantity * $purchaseLine->unit_price;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SCM\PurchaseLine  $purchaseLine
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseHeader $purchaseHeader, PurchaseLine $purchaseLine)
    {
        return \App\Http\Resources\SCM\PurchaseLine::make($purchaseLine);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SCM\PurchaseLine  $purchaseLine
     * @return \App\Http\Resources\SCM\PurchaseLine
     */
    public function update(Request $request, PurchaseHeader $purchaseHeader, PurchaseLine $purchaseLine)
    {
        $validated = $this->validate($request, [
            'item_id' => ['nullable', 'exists:items,id'],
            'item_variant_id' => ['nullable', 'exists:item_variants,id'],
            'description' => ['string', 'max:255'],
            'unit_price' => ['numeric'],
            'vat_percent' => ['numeric'],
            'vat_amount' => ['numeric'],
            'quantity' => ['numeric'],
            'line_amount' =>['numeric']
        ]);

        $purchaseLine->forceFill($validated);
        $this->onValidate($purchaseLine);
        $purchaseLine->save();

        return \App\Http\Resources\SCM\PurchaseLine::make($purchaseLine);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\PurchaseLine  $purchaseLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseHeader $purchaseHeader, PurchaseLine $purchaseLine)
    {
        if(!$purchaseLine->delete())
        {
            abort(500);
        }

        return Response::noContent();
    }

    static function getDashboardId()
    {
        return 'purchase_line';
    }

    public static function getDashboardParent()
    {
        return PurchaseHeaderController::class;
    }

    public static function isEditable(): bool
    {
        return true;
    }

    static function getDashboardFields(): array
    {
        return [
            [
                'field' => 'line_no',
                'headerName' => 'Zeilennr.', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'item_id',
                'headerName' => 'Artikelnr.', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'item_variant_id',
                'headerName' => 'Artikelvariantennr.', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'description',
                'headerName' => 'Beschreibung', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'unit_price',
                'headerName' => 'EK-Preis', // TODO i18n
                'sortable' => false,
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
            [
                'field' => 'vat_amount',
                'headerName' => 'MwSt. Betrag', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'quantity',
                'headerName' => 'Anzahl', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'line_amount',
                'headerName' => 'Zeilenbetrag', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'user_id',
                'headerName' => 'Benutzer-ID', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
        ];
    }
}
