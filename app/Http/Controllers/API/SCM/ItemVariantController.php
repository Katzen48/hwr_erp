<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\Item;
use App\Models\SCM\ItemVariant;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

class ItemVariantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Paginator
     */
    public function index(Item $item)
    {
        return $item->item_variants()->simplePaginate(100);
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
    public function show(ItemVariant $itemVariant)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SCM\ItemVariant  $itemVariant
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ItemVariant $itemVariant)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\ItemVariant  $itemVariant
     * @return \Illuminate\Http\Response
     */
    public function destroy(ItemVariant $itemVariant)
    {
        //
    }
}
