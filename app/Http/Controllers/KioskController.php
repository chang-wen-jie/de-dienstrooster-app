<?php

namespace App\Http\Controllers;

use App\Models\Employee;

class KioskController extends Controller
{
    public function index()
    {
        $active_employees = Employee::where('account_status', 'active')->get();
        $present_employees = $active_employees->filter(function ($employee) {
            return $employee->last_check_in > $employee->last_check_out;
        });

        return view('kiosk', [
            'active_employees' => $active_employees,
            'present_employees' => $present_employees,
        ]);
    }
}
