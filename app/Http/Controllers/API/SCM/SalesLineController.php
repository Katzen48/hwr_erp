<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\SalesHeader;
use App\Models\SCM\SalesLine;
use App\Traits\DashboardVisible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class SalesLineController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index(SalesHeader $salesHeader)
    {
        return \App\Http\Resources\SCM\SalesLine::collection($salesHeader->sales_lines()->simplePaginate(100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\SCM\SalesLine
     */
    public function store(SalesHeader $salesHeader, Request $request)
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

        $validated = array_merge($validated, ['sales_header_id' => $salesHeader->id, 'line_no' => $salesHeader->getNextLineNo()]);

        $salesLine = new SalesLine();
        $salesLine->forceFill($validated);
        $salesLine->user_id = auth()->user()->id ?? 1; // Todo;
        $salesLine->line_no = $salesHeader->getNextLineNo();
        $this->onValidate($salesLine);

        $salesLine->save();
        return \App\Http\Resources\SCM\SalesLine::make($salesLine);
    }

    public function onValidate(SalesLine $salesLine)
    {
        if($salesLine->isDirty('item_id'))
        {
            if (!$salesLine->item_variant_id)
            {
                if (!$salesLine->item_id)
                {
                    $salesLine->item_variant_id = null;
                }
                else
                {
                    if (!$salesLine->item->item_variants()->find($salesLine->item_variant_id))
                    {
                        $salesLine->item_variant_id = null;
                    }
                }
            }
        }

        if($salesLine->isDirty('item_variant_id'))
        {
            if($salesLine->item_variant_id) {
                $salesLine->description = $salesLine->item_variant->description;
                $salesLine->unit_price = $salesLine->item_variant->unit_price;
                $salesLine->vat_percent = $salesLine->item_variant->vat_percent;
            }
            else
            {
                $salesLine->description = "";
                $salesLine->unit_price = 0;
                $salesLine->vat_percent = 0;
            }
        }

        if($salesLine->isDirty('unit_price') && $salesLine->unit_price)
        {
            $this->calcAmounts($salesLine);
        }

        if($salesLine->isDirty('vat_percent') && $salesLine->vat_percent)
        {
            $this->calcAmounts($salesLine);
        }

        if($salesLine->isDirty('quantity') && $salesLine->quantity)
        {
            $this->calcAmounts($salesLine);
        }
    }

    public function calcAmounts(SalesLine $salesLine)
    {
        $salesLine->vat_amount = $salesLine->quantity * $salesLine->unit_price / (100 + $salesLine->vat_percent) * $salesLine->vat_percent;
        $salesLine->line_amount = $salesLine->quantity * $salesLine->unit_price;
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\SCM\SalesLine $salesLine
     * @return \App\Http\Resources\SCM\SalesHeader
     */
    public function show(SalesHeader $salesHeader, SalesLine $salesLine)
    {
        return \App\Http\Resources\SCM\SalesLine::make($salesLine);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\SCM\SalesLine $salesLine
     * @return \App\Http\Resources\SCM\SalesHeader
     */
    public function update(Request $request, SalesHeader $salesHeader, SalesLine $salesLine)
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

        $salesLine->forceFill($validated);
        $this->onValidate($salesLine);
        $salesLine->save();

        return \App\Http\Resources\SCM\SalesLine::make($salesLine->refresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\SCM\SalesLine $salesLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesHeader $salesHeader, SalesLine $salesLine)
    {
        if(!$salesLine->delete())
        {
            abort(500);
        }

        return Response::noContent();
    }

    static function getDashboardId()
    {
        return 'sales_line';
    }

    public static function getDashboardTitle(): string
    {
        return trans_choice('scm.sales_line', 2);
    }

    public static function getDashboardParent()
    {
        return SalesHeaderController::class;
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
                'headerName' => 'VK-Preis', // TODO i18n
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
                'headerName' => 'VK-Preis', // TODO i18n
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
