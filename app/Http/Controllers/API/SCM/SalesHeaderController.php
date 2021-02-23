<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\SalesHeader;
use App\Traits\DashboardVisible;
use Illuminate\Http\Request;

class SalesHeaderController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function index()
    {
        return \App\Http\Resources\SCM\SalesHeader::collection(SalesHeader::query()->simplePaginate(100));
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
     * @param  \App\Models\SCM\SalesHeader  $salesHeader
     * @return SalesHeader
     */
    public function show(SalesHeader $salesHeader)
    {
        return \App\Http\Resources\SCM\SalesHeader::make($salesHeader);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SCM\SalesHeader  $salesHeader
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalesHeader $salesHeader)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\SalesHeader  $salesHeader
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesHeader $salesHeader)
    {
        //
    }

    static function getDashboardId()
    {
        return 'sales_header';
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
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'employee_id',
                'headerName' => 'Verkäufer', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'outlet_id',
                'headerName' => 'Verkaufsstelle', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'storage_id',
                'headerName' => 'Lager', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'posting_date',
                'headerName' => 'Buchungsdatum', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'order_amount',
                'headerName' => 'Verkaufsbetrag', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
        ];
    }
}
