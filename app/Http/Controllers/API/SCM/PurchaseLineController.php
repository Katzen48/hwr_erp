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
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(PurchaseHeader $purchaseHeader)
    {
        return \App\Http\Resources\SCM\PurchaseLine::collection($purchaseHeader->purchase_lines()->whereNull(['archived_at'])->simplePaginate(100));
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
            'description' => ['nullable', 'string', 'max:255'],
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
        $purchaseLine->line_no = $purchaseHeader->getNextLineNo();
        $this->onValidate($purchaseLine);

        $purchaseLine->save();
        return \App\Http\Resources\SCM\PurchaseLine::make($purchaseLine->refresh());
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

        $purchaseHeader = $purchaseLine->purchaseHeader;

        if($purchaseHeader) {
            $purchaseHeader->recalculatePurchaseAmount();
            $purchaseHeader->save();
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
     * @param  int  $purchaseLine
     * @return \App\Http\Resources\SCM\PurchaseLine
     */
    public function show(PurchaseHeader $purchaseHeader, int $purchaseLine)
    {
        return \App\Http\Resources\SCM\PurchaseLine::make($purchaseHeader->purchase_lines()->findOrFail($purchaseLine));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SCM\PurchaseLine  $purchaseLine
     * @return \App\Http\Resources\SCM\PurchaseLine
     */
    public function update(Request $request, PurchaseHeader $purchaseHeader, int $purchaseLine)
    {
        $validated = $this->validate($request, [
            'item_id' => ['nullable', 'exists:items,id'],
            'item_variant_id' => ['nullable', 'exists:item_variants,id'],
            'description' => ['nullable', 'string', 'max:255'],
            'unit_price' => ['numeric'],
            'vat_percent' => ['numeric'],
            'vat_amount' => ['numeric'],
            'quantity' => ['numeric'],
            'line_amount' =>['numeric']
        ]);

        $line = $purchaseHeader->purchase_lines()->findOrFail($purchaseLine);

        $line->forceFill($validated);
        $this->onValidate($line);
        $line->save();

        return \App\Http\Resources\SCM\PurchaseLine::make($line->refresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\PurchaseLine  $purchaseLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseHeader $purchaseHeader, int $purchaseLine)
    {
        $line = $purchaseHeader->purchase_lines()->findOrFail($purchaseLine);

        if(!$line->delete())
        {
            abort(500);
        }

        return Response::noContent();
    }

    static function getDashboardId()
    {
        return 'purchase_line';
    }

    public static function getDashboardTitle(): string
    {
        return trans_choice('scm.purchase_line', 2);
    }

    public static function getDashboardParent()
    {
        return PurchaseHeaderController::class;
    }

    public static function isEditable(): bool
    {
        return true;
    }

    public static function getPrimaryKey(): string
    {
        return 'line_no';
    }

    public static function getEditFields(): array
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
                'editable' => true,
                'type' => 'numeric',
            ],
            [
                'field' => 'item_variant_id',
                'headerName' => 'Artikelvariantennr.', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => true,
                'type' => 'numeric',
            ],
            [
                'field' => 'description',
                'headerName' => 'Beschreibung', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => true,
            ],
            [
                'field' => 'unit_price',
                'headerName' => 'EK-Preis', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => true,
                'type' => 'currency',
            ],
            [
                'field' => 'vat_percent',
                'headerName' => 'MwSt. %', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => true,
                'type' => 'numeric',
            ],
            [
                'field' => 'vat_amount',
                'headerName' => 'MwSt. Betrag', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => true,
                'type' => 'currency',
            ],
            [
                'field' => 'quantity',
                'headerName' => 'Anzahl', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => true,
                'type' => 'numeric',
            ],
            [
                'field' => 'line_amount',
                'headerName' => 'Zeilenbetrag', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => true,
                'type' => 'currency',
            ],
            [
                'field' => 'user_id',
                'headerName' => 'Benutzer-ID', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
                'type' => 'numeric',
            ],
        ];
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
                'type' => 'numeric',
            ],
            [
                'field' => 'item_variant_id',
                'headerName' => 'Artikelvariantennr.', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
                'type' => 'numeric',
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
                'type' => 'currency',
            ],
            [
                'field' => 'vat_percent',
                'headerName' => 'MwSt. %', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
                'type' => 'numeric',
            ],
            [
                'field' => 'vat_amount',
                'headerName' => 'MwSt. Betrag', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
                'type' => 'currency',
            ],
            [
                'field' => 'quantity',
                'headerName' => 'Anzahl', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
                'type' => 'numeric',
            ],
            [
                'field' => 'line_amount',
                'headerName' => 'Zeilenbetrag', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
                'type' => 'currency',
            ],
            [
                'field' => 'user_id',
                'headerName' => 'Benutzer-ID', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
                'type' => 'numeric',
            ],
        ];
    }
}
