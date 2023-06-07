<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Personeel ophalen.
     */
    public function index()
    {
        $employees = Employee::orderBy('account_status')->paginate(10);

        return view('admin.admin', ['employees' => $employees]);
    }

    /**
     * Personeel toevoegen
     */
    public function create()
    {
        return view('admin.create');
    }

    public function store(Request $request)
    {
        $employee_rfid = $request->input('rfid');
        $employee_name = $request->input('name');
        $employee_email = $request->input('email');
        $employee_password = $request->input('password');

        $employee_data = [
            'rfid' => $employee_rfid,
            'name' => $employee_name,
            'email' => $employee_email,
            'password' => Hash::make($employee_password),
            'account_type' => 'user',
            'account_status' => 'active',
        ];
        Employee::create($employee_data);

        return redirect('/admin')->with('success', 'Het personeel is succesvol toegevoegd.');
    }

    /**
     * Desbetreffende personeelsgegevens tonen.
     */
    public function edit(int $id)
    {
        $employee = Employee::findOrFail($id);

        return view('admin.edit', ['employee' => $employee]);
    }

    /**
     * Desbetreffende personeel's personeelsgegevens aanpassen.
     */
    public function update(Request $request, int $id)
    {
        $employee = Employee::findOrFail($id);

        $employee_rfid = $request->input('rfid');
        $employee_name = $request->input('name');
        $employee_account_type = $request->input('account_type');
        $employee_account_is_active = $request->input('account_status');

        $employee_data = [
            'rfid' => $employee_rfid,
            'name' => $employee_name,
            'account_type' => $employee_account_type,
            'account_status' => $employee_account_is_active ? 'active' : 'inactive',
        ];
        $employee->update($employee_data);

        return redirect('/admin')->with('success', 'De personeelsgegevens zijn succesvol aangepast.');
    }
}
