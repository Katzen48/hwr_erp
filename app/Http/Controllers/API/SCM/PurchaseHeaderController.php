<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\PurchaseHeader;
use App\Traits\DashboardVisible;
use Illuminate\Http\Request;

class PurchaseHeaderController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function index()
    {
        return \App\Http\Resources\SCM\PurchaseHeader::collection(PurchaseHeader::query()->simplePaginate(100));
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
     * @param  \App\Models\SCM\PurchaseHeader  $purchaseHeader
     * @return PurchaseHeader
     */
    public function show(PurchaseHeader $purchaseHeader)
    {
        return \App\Http\Resources\SCM\PurchaseHeader::make($purchaseHeader);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SCM\PurchaseHeader  $purchaseHeader
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseHeader $purchaseHeader)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\PurchaseHeader  $purchaseHeader
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseHeader $purchaseHeader)
    {
        //
    }
}