<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\PurchaseHeader;
use App\Models\SCM\PurchaseLine;
use Illuminate\Http\Request;

class PurchaseLineController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function index(PurchaseHeader $purchaseHeader)
    {
        return $purchaseHeader->purchase_lines()->simplePaginate(100);
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
     * @param  \App\Models\SCM\PurchaseLine  $purchaseLine
     * @return \Illuminate\Http\Response
     */
    public function show(PurchaseLine $purchaseLine)
    {
        return $purchaseLine;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SCM\PurchaseLine  $purchaseLine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PurchaseLine $purchaseLine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\PurchaseLine  $purchaseLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(PurchaseLine $purchaseLine)
    {
        //
    }
}
