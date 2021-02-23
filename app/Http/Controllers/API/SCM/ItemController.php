<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\Item;
use App\Traits\DashboardVisible;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;

class ItemController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return Paginator
     */
    public function index()
    {
        return \App\Http\Resources\SCM\Item::collection(Item::query()->simplePaginate(100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param Item $item
     * @return Response
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
     * @return Response
     */
    public function update(Request $request, Item $item)
    {
        // TODO autogenerate from "fields" (editable etc.)

        $this->validate($request, [
            'id' => 'required|integer|size:' . $item->id,
            'description' => 'required|integer',
            'storage_posting_method' => Rule::in(['FIFO', 'LIFO']),
        ]);

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
        //
    }

    static function getDashboardId()
    {
        return 'item';
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
