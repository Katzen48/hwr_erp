<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\PurchaseHeader;
use App\Models\SCM\PurchaseLine;
use App\Traits\DashboardVisible;
use Illuminate\Http\Request;

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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseHeader $purchaseHeader, PurchaseLine $purchaseLine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\PurchaseLine  $purchaseLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseHeader $purchaseHeader, PurchaseLine $purchaseLine)
    {
        //
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
