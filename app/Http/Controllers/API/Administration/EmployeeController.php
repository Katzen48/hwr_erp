<?php

namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Models\Administration\Employee;
use App\Traits\DashboardVisible;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Pagination\Paginator
     */
    public function index()
    {
        return \App\Http\Resources\Administration\Employee::collection(Employee::query()->simplePaginate(100));
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
     * @param  \App\Models\Administration\Employee  $employee
     * @return Employee
     */
    public function show(Employee $employee)
    {
        return \App\Http\Resources\Administration\Employee::make($employee);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Administration\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Employee $employee)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Administration\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        //
    }

    static function getDashboardId()
    {
        return 'employee';
    }

    static function getDashboardFields()
    {
        return [
            [
                'field' => 'id',
                'title' => 'ID', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
            [
                'field' => 'first_name',
                'title' => 'Vorname', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
            [
                'field' => 'last_name',
                'title' => 'Last Name', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
            [
                'field' => 'position',
                'title' => 'Position', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
            [
                'field' => 'purchaser',
                'title' => 'Einkäufer', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
            [
                'field' => 'salesperson',
                'title' => 'Verkäufer', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
        ];
    }

    public static function isEditable(): bool
    {
        return true;
    }
}
