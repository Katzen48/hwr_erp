<?php

namespace App\Http\Controllers\API\Administration;

use App\Http\Controllers\Controller;
use App\Models\Administration\Employee;
use App\Traits\DashboardVisible;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class EmployeeController extends Controller
{
    use DashboardVisible;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return \App\Http\Resources\Administration\Employee::collection(Employee::query()->simplePaginate(100));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\Administration\Employee
     */
    public function store(Request $request)
    {
        $validated = $this->validate($request, [
            'outlet_id' => ['nullable', 'exists:employees,id'],
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'position' => ['string', 'max:255'],
            'purchaser' => ['boolean'],
            'salesperson' => ['boolean']
        ]);

        $employee = new Employee();
        $employee->forceFill($validated);

        $employee->save();
        $employee = $employee->refresh();
        return \App\Http\Resources\Administration\Employee::make($employee);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Administration\Employee  $employee
     * @return \App\Http\Resources\Administration\Employee
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
     * @return \App\Http\Resources\Administration\Employee
     */
    public function update(Request $request, Employee $employee)
    {
        $validated = $this->validate($request, [
            'outlet_id' => ['nullable', 'exists:employees,id'],
            'first_name' => ['string', 'max:255'],
            'last_name' => ['string', 'max:255'],
            'position' => ['string', 'max:255'],
            'purchaser' => ['boolean'],
            'salesperson' => ['boolean']
        ]);

        $employee->forceFill($validated);
        $employee->save();

        $employee = $employee->refresh();
        return  \App\Http\Resources\Administration\Employee::make($employee);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Administration\Employee  $employee
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employee $employee)
    {
        if(!$employee->delete())
        {
            abort(500);
        }

        return Response::noContent();
    }

    static function getDashboardId()
    {
        return 'employee';
    }

    public static function getDashboardTitle(): string
    {
        return trans_choice('scm.employee', 2);
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
                'field' => 'first_name',
                'headerName' => 'Vorname', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
            [
                'field' => 'last_name',
                'headerName' => 'Last Name', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
            [
                'field' => 'position',
                'headerName' => 'Position', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
            [
                'field' => 'purchaser',
                'headerName' => 'Einkäufer', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => true,
                'type' => 'boolean',
            ],
            [
                'field' => 'salesperson',
                'headerName' => 'Verkäufer', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => true,
                'type' => 'boolean',
            ],
        ];
    }

    static function getDashboardFields()
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
                'field' => 'first_name',
                'headerName' => 'Vorname', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
            [
                'field' => 'last_name',
                'headerName' => 'Last Name', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
            [
                'field' => 'position',
                'headerName' => 'Position', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
            ],
            [
                'field' => 'purchaser',
                'headerName' => 'Einkäufer', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
                'type' => 'boolean',
            ],
            [
                'field' => 'salesperson',
                'headerName' => 'Verkäufer', // TODO i18n
                'sortable' => true,
                'filter' => true,
                'editable' => false,
                'type' => 'boolean',
            ],
        ];
    }

    public static function isEditable(): bool
    {
        return true;
    }
}
