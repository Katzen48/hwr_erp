<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\Storage;
use App\Traits\DashboardVisible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class StorageController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function index()
    {
        return \App\Http\Resources\SCM\Storage::collection(Storage::query()->simplePaginate(100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\SCM\Storage
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'description' => ['string', 'max:255'],
            'address' => ['string', 'max:255'],
            'postcode' => ['string', 'max:255'],
            'state' => ['string', 'max:255'],
            'country' => ['string', 'max:255']
        ]);

        $storage = Storage::query()->forceCreate($validated);

        $storage = $storage->refresh();
        return \App\Http\Resources\SCM\Storage::make($storage);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SCM\Storage  $storage
     * @return Storage
     */
    public function show(Storage $storage)
    {
        return \App\Http\Resources\SCM\Storage::make($storage);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SCM\Storage  $storage
     * @return \App\Http\Resources\SCM\Storage
     */
    public function update(Request $request, Storage $storage)
    {
        $validated = $this->validate($request, [
            'description' => ['string', 'max:255'],
            'address' => ['string', 'max:255'],
            'postcode' => ['string', 'max:255'],
            'state' => ['string', 'max:255'],
            'country' => ['string', 'max:255']
        ]);

        $storage->forceFill($validated);
        $storage->save();
        $storage = $storage->refresh();
        return \App\Http\Resources\SCM\Storage::make($storage);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\Storage  $storage
     * @return \Illuminate\Http\Response
     */
    public function destroy(Storage $storage)
    {
        if(!$storage->delete()) {
            abort(500);
        };

        return Response::noContent();
    }

    static function getDashboardId()
    {
        return 'storage';
    }

    public static function getDashboardTitle(): string
    {
        return trans_choice('scm.storage', 2);
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
