<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Presence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index() {
        $present_employees = Employee::where('active', true)->where('present', true)->paginate();
        $absent_employees = Employee::where('active', true)->where('present', false)->paginate();

        return view('dashboard', ['present_users' => $present_employees, 'absent_users' => $absent_employees]);
    }

    public function show(int $id) {
        $employee = Employee::findOrFail($id);
        $present = !$employee->present;
        $activity_time = now();
        $presence_data = ['present' => $present];

        if ($present) {
            $presence_data['last_check_in'] = $activity_time;
        } else {
            $presence_data['last_check_out'] = $activity_time;
        }

        $employee->update($presence_data);

        return redirect()->back();
    }

    public function edit(int $id) {
        $employee = Employee::findOrFail($id);

        return view('admin.edit', ['employee' => $employee]);
    }

    public function update(int $id)
    {
        $employee = Employee::findOrFail($id);
        $input = request('name');
        $checkbox = request('active');
        $employee->update(['name' => $input, 'active' => $checkbox]);

        return redirect('/users/admin');
    }

    public function schedule(int $id) {
        $start = request('start');
        $end = request('end');
        $absence = request('absence');

        $employee_shift = Presence::where('employee_id', $id)->whereDate('start', $start)->first();
        $employee_sickness = Presence::where('employee_id', $id)->whereDate('start', $start)->where('called_in_sick', true);

        if ($absence && $employee_shift) {
            $employee_shift->update([
                'status_id' => $absence === 'leave' ? 2 : 1,
                'end' => $end,
                'called_in_sick' => $absence === 'sick',
            ]);
        } else {
            if ($employee_sickness) {
                return 'Gebruiker is ziek!';
            } else {
                Presence::create([
                    'employee_id' => $id,
                    'status_id' => $absence === 'leave' ? 2 : 1,
                    'start' => $start,
                    'end' => $end,
                    'called_in_sick' => $absence === 'sick',
                ]);
            }
        }

        return redirect('/users/admin');
    }

    public function calendar() {
        return view('calendar');
    }

    public function admin() {
        $session_role = Auth::user()->role_id;
        $employees = Employee::all()->sortByDesc('active');

        if ($session_role === 1) {
            return view('admin.admin', ['employees' => $employees]);
        }

        return redirect()->back();
    }
}
