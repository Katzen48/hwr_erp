<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\Item;
use App\Traits\DashboardVisible;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\In;

class ItemController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return \App\Http\Resources\SCM\Item::collection(Item::query()->simplePaginate(100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \App\Http\Resources\SCM\Item
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'description' => ['string', 'max:255'],
            'storage_posting_method' => [Rule::in('LIFO', 'FIFO')]
        ]);

        $item = Item::query()->forceCreate($validated);

        $item = $item->refresh();
        return \App\Http\Resources\SCM\Item::make($item);
    }

    /**
     * Display the specified resource.
     *
     * @param Item $item
     * @return \App\Http\Resources\SCM\Item
     */
    public function show(Item $item)
    {
        return \App\Http\Resources\SCM\Item::make($item);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Item $item
     * @return \App\Http\Resources\SCM\Item
     */
    public function update(Request $request, Item $item)
    {
        // TODO autogenerate from "fields" (editable etc.)

        $validated = $this->validate($request, [
            'description' => ['string', 'max:255'],
            'storage_posting_method' => ['required', Rule::in('LIFO', 'FIFO')]
        ]);

        $item->forceFill($validated);
        $item->save();
        $item = $item->refresh();

        return \App\Http\Resources\SCM\Item::make($item);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Item $item
     * @return Response
     */
    public function destroy(Item $item)
    {
        if(!$item->delete()){
            abort(500);
        }

        return \Illuminate\Support\Facades\Response::noContent();
    }

    static function getDashboardId()
    {
        return 'item';
    }

    public static function isEditable(): bool
    {
        return true;
    }

    public static function getDashboardTitle(): string
    {
        return trans_choice('scm.item', 2);
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
                'field' => 'description',
                'headerName' => 'Beschreibung', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => true,
            ],
            [
                'field' => 'storage_posting_method',
                'headerName' => 'Lagerbuchungsmethode', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => true,
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
                'field' => 'description',
                'headerName' => 'Beschreibung', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => true,
            ],
            [
                'field' => 'storage_posting_method',
                'headerName' => 'Lagerbuchungsmethode', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
        ];
    }
}
