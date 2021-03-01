<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\Vendor;
use App\Traits\DashboardVisible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class VendorController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function index()
    {
        return \App\Http\Resources\SCM\Vendor::collection(Vendor::query()->simplePaginate(100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\SCM\Vendor
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'name' => ['string', 'max:255'],
            'address' => ['string', 'max:255'],
            'postcode' => ['string', 'max:255'],
            'state' => ['string', 'max:255'],
            'country' => ['string', 'max:255']
        ]);

        $vendor = Vendor::query()->forceCreate($validated);

        $vendor = $vendor->refresh();
        return \App\Http\Resources\SCM\Vendor::make($vendor);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SCM\Vendor  $vendor
     * @return Vendor
     */
    public function show(Vendor $vendor)
    {
        return \App\Http\Resources\SCM\Vendor::make($vendor);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SCM\Vendor  $vendor
     * @return \App\Http\Resources\SCM\Vendor
     */
    public function update(Request $request, Vendor $vendor)
    {
        $validated = $this->validate($request, [
            'name' => ['string', 'max:255'],
            'address' => ['string', 'max:255'],
            'postcode' => ['string', 'max:255'],
            'state' => ['string', 'max:255'],
            'country' => ['string', 'max:255']
        ]);

        $vendor->forceFill($validated);
        $vendor->save();
        $vendor = $vendor->refresh();

        return \App\Http\Resources\SCM\Vendor::make($vendor);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        if(!$vendor->delete()){
            abort(500);
        }

        return Response::noContent();
    }

    static function getDashboardId()
    {
        return 'vendor';
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
                'field' => 'name',
                'headerName' => 'Name', // TODO i18n
                'sortable' => false,
                'filter' => false,
                'editable' => false,
            ],
            [
                'field' => 'address',
                'headerName' => 'Adresse.', // TODO i18n
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
