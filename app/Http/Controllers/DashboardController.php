<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Presence;
use Illuminate\Support\Facades\Auth;

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

        $presence = Presence::where('employee_id', $id)->whereDate('start', $start)->first();

        if ($presence) {
            $presence->update([
                'status_id' => $absence === 'leave' ? 2 : 1,
                'end' => $end,
                'called_in_sick' => $absence === 'sick',
            ]);
        } else {
            Presence::create([
                'employee_id' => $id,
                'status_id' => $absence === 'leave' ? 2 : 1,
                'start' => $start,
                'end' => $end,
                'called_in_sick' => $absence === 'sick',
            ]);
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
