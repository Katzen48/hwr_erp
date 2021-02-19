<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\SalesHeader;
use App\Traits\DashboardVisible;
use Illuminate\Http\Request;

class SalesHeaderController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function index()
    {
        return \App\Http\Resources\SCM\SalesHeader::collection(SalesHeader::query()->simplePaginate(100));
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
     * @param  \App\Models\SCM\SalesHeader  $salesHeader
     * @return SalesHeader
     */
    public function show(SalesHeader $salesHeader)
    {
        return \App\Http\Resources\SCM\SalesHeader::make($salesHeader);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SCM\SalesHeader  $salesHeader
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalesHeader $salesHeader)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\SalesHeader  $salesHeader
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesHeader $salesHeader)
    {
        //
    }
}