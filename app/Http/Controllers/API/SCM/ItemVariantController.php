<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\Item;
use App\Models\SCM\ItemVariant;
use App\Traits\DashboardVisible;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

class ItemVariantController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @param Item $item
     * @return Paginator
     */
    public function index(Item $item)
    {
        return \App\Http\Resources\SCM\ItemVariant::collection($item->item_variants()->simplePaginate(100));
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
     * @param  \App\Models\SCM\ItemVariant  $itemVariant
     * @return \Illuminate\Http\Response
     */
    public function show(Item $item, ItemVariant $itemVariant)
    {
        return \App\Http\Resources\SCM\ItemVariant::make($itemVariant);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param Item $item
     * @param \App\Models\SCM\ItemVariant $itemVariant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Item $item, ItemVariant $itemVariant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\ItemVariant  $itemVariant
     * @return \Illuminate\Http\Response
     */
    public function destroy(Item $item, ItemVariant $itemVariant)
    {
        //
    }

    public static function getDashboardParent()
    {
        return 'Item';
    }
}
