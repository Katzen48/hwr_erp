<?php

namespace App\Http\Controllers\API\SCM;

use App\Http\Controllers\Controller;
use App\Models\SCM\SalesHeader;
use App\Models\SCM\SalesLine;
use App\Traits\DashboardVisible;
use Illuminate\Http\Request;

class SalesLineController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function index(SalesHeader $salesHeader)
    {
        return \App\Http\Resources\SCM\SalesHeader::collection($salesHeader->sales_lines()->simplePaginate(100));
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
     * @param  \App\Models\SCM\SalesLine  $salesLine
     * @return SalesLine
     */
    public function show(SalesHeader $salesHeader, SalesLine $salesLine)
    {
        return \App\Http\Resources\SCM\SalesHeader::make($salesLine);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\SCM\SalesLine  $salesLine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SalesHeader $salesHeader, SalesLine $salesLine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SCM\SalesLine  $salesLine
     * @return \Illuminate\Http\Response
     */
    public function destroy(SalesHeader $salesHeader, SalesLine $salesLine)
    {
        //
    }
}
