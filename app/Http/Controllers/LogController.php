<?php

namespace App\Http\Controllers;

use App\Models\Employee;

class LogController extends Controller
{
    public function index(int $id)
    {
        $employee = Employee::findOrFail($id);

        return view('admin.logs', ['employee' => $employee]);
    }
}
