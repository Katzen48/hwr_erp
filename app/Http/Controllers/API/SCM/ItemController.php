<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\Item;
use App\Traits\DashboardVisible;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

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
        //
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
}
