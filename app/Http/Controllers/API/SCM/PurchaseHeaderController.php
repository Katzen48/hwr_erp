<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\PurchaseHeader;
use App\Services\SCM\PurchasePost;
use App\Traits\DashboardVisible;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;

class PurchaseHeaderController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return \App\Http\Resources\SCM\PurchaseHeader::collection(PurchaseHeader::query()->whereNull(['archived_at'])->simplePaginate(100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \App\Http\Resources\SCM\PurchaseHeader
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'outlet_id' => ['nullable', 'exists:outlets,id'],
            'storage_id' => ['nullable', 'exists:storages,id'],
            'delivery_date' => ['date'],
            'posting_date' => ['date'],
            'purchase_amount' => ['numeric']
        ]);

        $purchaseHeader = new PurchaseHeader();
        $purchaseHeader->forceFill($validated);

        if(!$purchaseHeader->employee_id && auth()->check()) {
            $purchaseHeader->employee_id = auth()->user()->employee->id ?? null;
        }

        $this->onValidate($purchaseHeader);

        $purchaseHeader->save();
        $purchaseHeader = $purchaseHeader->refresh();
        return \App\Http\Resources\SCM\PurchaseHeader::make($purchaseHeader);
    }

    public function onValidate(PurchaseHeader $purchaseHeader)
    {
        if($purchaseHeader->isDirty('employee_id') && $purchaseHeader->employee_id)
        {
            $purchaseHeader->outlet_id = $purchaseHeader->employee->outlet_id;
        }

        if($purchaseHeader->isDirty('outlet_id') && $purchaseHeader->outlet_id)
        {
            $purchaseHeader->storage_id = $purchaseHeader->outlet->local_storage_id;
        }

        if(!$purchaseHeader->posting_date)
        {
            $purchaseHeader->posting_date = Carbon::now();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\SCM\PurchaseHeader $purchaseHeader
     * @return \App\Http\Resources\SCM\PurchaseHeader
     */
    public function show(PurchaseHeader $purchaseHeader)
    {
        return \App\Http\Resources\SCM\PurchaseHeader::make($purchaseHeader);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\SCM\PurchaseHeader $purchaseHeader
     * @return \App\Http\Resources\SCM\PurchaseHeader
     */
    public function update(Request $request, PurchaseHeader $purchaseHeader)
    {
        $validated = $this->validate($request, [
            'vendor_id' => ['nullable', 'exists:vendors,id'],
            'employee_id' => ['nullable', 'exists:employees,id'],
            'outlet_id' => ['nullable', 'exists:outlets,id'],
            'storage_id' => ['nullable', 'exists:storages,id'],
            'delivery_date' => ['date'],
            'posting_date' => ['date'],
            'purchase_amount' => ['numeric']
        ]);

        $purchaseHeader->forceFill($validated);
        $this->onValidate($purchaseHeader);
        $purchaseHeader->save();
        $purchaseHeader = $purchaseHeader->refresh();

        return \App\Http\Resources\SCM\PurchaseHeader::make($purchaseHeader);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\SCM\PurchaseHeader $purchaseHeader
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseHeader $purchaseHeader)
    {
        if(!$purchaseHeader->delete())
        {
            abort(500);
        }

        return Response::noContent();
    }

    /**
     * @param PurchaseHeader $purchaseHeader
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function post(PurchaseHeader $purchaseHeader)
    {
        PurchasePost::post($purchaseHeader);

        return \response('', 204);
    }

    static function getDashboardId()
    {
        return 'purchase_header';
    }

    public static function getDashboardTitle(): string
    {
        return trans_choice('scm.purchase_header', 2);
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
                'field' => 'vendor_id',
                'headerName' => 'Lieferant', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => true,
            ],
            [
                'field' => 'employee_id',
                'headerName' => 'Einkäufer', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => true,
            ],
            [
                'field' => 'outlet_id',
                'headerName' => 'Verkaufsstelle', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => true,
            ],
            [
                'field' => 'storage_id',
                'headerName' => 'Lager', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => true,
            ],
            [
                'field' => 'delivery_date',
                'headerName' => 'Lieferdatum', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => true,
                'type' => 'date',
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
                'field' => 'vendor_id',
                'headerName' => 'Lieferant', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'employee_id',
                'headerName' => 'Einkäufer', // TODO i18n
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
                'field' => 'delivery_date',
                'headerName' => 'Lieferdatum', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
                'type' => 'date',
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
                'field' => 'purchase_amount',
                'headerName' => 'Einkaufsbetrag', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
        ];
    }
}
