<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\Outlet;
use App\Traits\DashboardVisible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Validation\Rules\Exists;

class OutletController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return \App\Http\Resources\SCM\Outlet::collection(Outlet::query()->simplePaginate(100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\SCM\Outlet
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'local_storage_id' => ['exists:storages,id'],
            'shipping_storage_id' => ['exists:storages,id'],
            'description' => ['string', 'max:255'],
            'address' => ['string', 'max:255'],
            'postcode' => ['string', 'max:255'],
            'state' => ['string', 'max:255'],
            'country' => ['string', 'max:255']
        ]);

        $outlet = Outlet::query()->forceCreate($validated);

        $outlet = $outlet->refresh();
        return \App\Http\Resources\SCM\Outlet::make($outlet);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SCM\Outlet  $outlet
     * @return \App\Http\Resources\SCM\Outlet
     */
    public function show(Outlet $outlet)
    {
        return \App\Http\Resources\SCM\Outlet::make($outlet);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SCM\Outlet  $outlet
     * @return \App\Http\Resources\SCM\Outlet
     */
    public function update(Request $request, Outlet $outlet)
    {
        $validated = $this->validate($request, [
            'local_storage_id' => ['nullable', 'exists:storages,id'],
            'shipping_storage_id' => ['nullable', 'exists:storages,id'],
            'description' => ['string', 'max:255'],
            'address' => ['string', 'max:255'],
            'postcode' => ['string', 'max:255'],
            'state' => ['string', 'max:255'],
            'country' => ['string', 'max:255']
        ]);

        $outlet->forceFill($validated);
        $outlet->save();
        $outlet = $outlet->refresh();

        return \App\Http\Resources\SCM\Outlet::make($outlet);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\Outlet  $outlet
     * @return \Illuminate\Http\Response
     */
    public function destroy(Outlet $outlet)
    {
        if(!$outlet->delete())
        {
            abort(500);
        }

        return Response::noContent();
    }

    static function getDashboardId()
    {
        return 'outlet';
    }

    public static function getDashboardTitle(): string
    {
        return trans_choice('scm.outlet', 2);
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
                'field' => 'local_storage_id',
                'headerName' => 'Vorort-Lager', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
            [
                'field' => 'shipping_storage_id',
                'headerName' => 'Versand-Lager', // TODO i18n
                'sortable' => true,
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
                'field' => 'address',
                'headerName' => 'Adresse', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'postcode',
                'headerName' => 'PLZ', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'state',
                'headerName' => 'Bundesland', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'country',
                'headerName' => 'Land', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
        ];
    }
}
