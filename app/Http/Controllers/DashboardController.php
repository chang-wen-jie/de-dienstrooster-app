<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Event;
use Carbon\Carbon;
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
        $start_formatted = Carbon::parse($start)->format('Y-m-d');
        $end = request('end');
        $absence = request('absence');

        $planned_shift = Event::where('employee_id', $id)->whereDate('start', $start_formatted)->where('status_id', 1);
        $vacation = Event::where('employee_id', $id)->whereDate('start', $start_formatted)->where('status_id', 2);
        $medical_leave = Event::where('employee_id', $id)->whereDate('start', $start_formatted)->where('sick', true);

        if ($absence && $planned_shift->exists()) {
            if ($medical_leave->exists()) {
                return redirect()->back()->withErrors(['error' => 'Dit personeel is al ziekgemeld op deze datum!']);
            } else {
                $planned_shift->update([
                    'status_id' => $absence === 'leave' ? 2 : 1,
                    'end' => $end,
                    'sick' => $absence === 'sick',
                ]);
            }
        } else {
            if ($planned_shift->exists()) {
                return redirect()->back()->withErrors(['error' => 'Dit personeel staat al ingeroosterd op deze datum!']);
            } else if ($vacation->exists()) {
                return redirect()->back()->withErrors(['error' => 'Dit personeel is roostervrij op deze datum!']);
            } else if ($medical_leave->exists()) {
                return redirect()->back()->withErrors(['error' => 'Dit personeel is ziek op deze datum!']);
            } else {
                Event::create([
                    'employee_id' => $id,
                    'status_id' => $absence === 'leave' ? 2 : 1,
                    'start' => $start,
                    'end' => $end,
                    'sick' => $absence === 'sick',
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

    public function test(int $id) {
        $medical_leave = Event::where('employee_id', $id)->whereDate('start', now())->where('sick', true);
        $medical_leave->update(['sick' => false]);

        return redirect()->back();
    }
}
