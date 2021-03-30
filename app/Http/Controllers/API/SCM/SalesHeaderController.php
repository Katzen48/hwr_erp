<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Http\Resources\SCM\PurchaseHeader;
use App\Models\SCM\SalesHeader;
use App\Services\SCM\SalesPost;
use App\Traits\DashboardVisible;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

class SalesHeaderController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return \App\Http\Resources\SCM\SalesHeader::collection(SalesHeader::query()->whereNull(['archived_at'])->simplePaginate(100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\SCM\SalesHeader
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'employee_id' => ['nullable', 'exists:employees,id'],
            'outlet_id' => ['nullable', 'exists:outlets,id'],
            'storage_id' => ['nullable', 'exists:storages,id'],
            'posting_date' => ['date'],
            'purchase_amount' => ['numeric']
        ]);

        $salesHeader = new SalesHeader();
        $salesHeader->forceFill($validated);

        if(!$salesHeader->employee_id && auth()->check()) {
            $salesHeader->employee_id = auth()->user()->employee->id ?? null;
        }

        $this->onValidate($salesHeader);

        $salesHeader->save();
        $salesHeader = $salesHeader->refresh();
        return \App\Http\Resources\SCM\SalesHeader::make($salesHeader);

    }

    public function onValidate(SalesHeader $salesHeader)
    {
        if($salesHeader->isDirty('employee_id') && $salesHeader->employee_id)
        {
            $salesHeader->outlet_id = $salesHeader->employee->outlet_id;
        }

        if($salesHeader->isDirty('outlet_id') && $salesHeader->outlet_id)
        {
            $salesHeader->storage_id = $salesHeader->outlet->local_storage_id;
        }

        if(!$salesHeader->posting_date)
        {
            $salesHeader->posting_date = Carbon::now();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SCM\SalesHeader  $salesHeader
     * @return \App\Http\Resources\SCM\SalesHeader
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
     * @return \App\Http\Resources\SCM\SalesHeader
     */
    public function update(Request $request, SalesHeader $salesHeader)
    {
        $validated = $this->validate($request, [
            'employee_id' => ['nullable', 'exists:employees,id'],
            'outlet_id' => ['nullable', 'exists:outlets,id'],
            'storage_id' => ['nullable', 'exists:storages,id'],
            'posting_date' => ['date'],
            'purchase_amount' => ['numeric']
        ]);

        $salesHeader->forceFill($validated);
        $this->onValidate($salesHeader);
        $salesHeader->save();
        $salesHeader = $salesHeader->refresh();

        return \App\Http\Resources\SCM\SalesHeader::make($salesHeader);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\SalesHeader  $salesHeader
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesHeader $salesHeader)
    {
        if(!$salesHeader->delete())
        {
            abort(500);
        }

        return Response::noContent();
    }

    public function post(SalesHeader $salesHeader)
    {
        SalesPost::post($salesHeader);

        return \response('', 204);
    }

    static function getDashboardId()
    {
        return 'sales_header';
    }

    public static function getDashboardTitle(): string
    {
        return trans_choice('scm.sales_header', 2);
    }

    public static function isEditable(): bool
    {
        return true;
    }

    public static function getActions(): array
    {
        return [
            'post' => [
                'title' => __('scm.post'),
                'url' => 'post',
            ],
        ];
    }

    public static function getEditFields(): array
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
                'field' => 'employee_id',
                'headerName' => 'Verkäufer', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => true,
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
                'editable' => true,
            ],
            [
                'field' => 'posting_date',
                'headerName' => 'Buchungsdatum', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => true,
                'type' => 'date',
            ],
        ];
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
                'type' => 'date',
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
