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

    // Personeel in- of uitchecken
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

    // Personeelsgegevens ophalen
    public function edit(int $id) {
        $employee = Employee::findOrFail($id);

        return view('admin.edit', ['employee' => $employee]);
    }

    // Personeelsgegevens veranderen
    public function update(int $id)
    {
        $employee = Employee::findOrFail($id);
        $input = request('name');
        $checkbox = request('active');
        $employee->update(['name' => $input, 'active' => $checkbox]);

        return redirect('/users/admin');
    }

    // Diensten, ziekteverzuimen en vakantieverloven registreren
    public function scheduleEvent(int $id) {
        $shift_date = request('shift-date');
        $shift_start = request('shift-start');
        $shift_end = request('shift-end');
        $shift_start_formatted = Carbon::parse($shift_date.' '.$shift_start)->format('Y-m-d H:i:s');
        $shift_end_formatted = Carbon::parse($shift_date.' '.$shift_end)->format('Y-m-d H:i:s');

        $absence_reason = request('absence-reason');
        $absence_start = request('absence-start');
        $absence_end = request('absence-end');

        $employee_shift = Event::where('employee_id', $id)->where('status_id', 1);
        $employee_leave = Event::where('employee_id', $id)->where('status_id', 2);
        $employee_medical_leave = Event::where('employee_id', $id)->where('sick', true);

        if ($absence_reason && $employee_shift->whereDate('start', $absence_start)->exists()) {
            if ($absence_reason === 'leave') {
                return redirect()->back()->withErrors(['error' => 'Dit personeel staat ingeroosterd op de startdatum!']);
            } else {
                $employee_shift->update([
                    'start' => $absence_start,
                    'end' => $absence_end,
                    'sick' => true,
                ]);
            }
        } else {
            if ($employee_shift->whereDate('start', $shift_date)->exists()) {
                return redirect()->back()->withErrors(['error' => 'Dit personeel staat al ingeroosterd op deze datum!']);
            } else if ($employee_leave->whereDate('end', '>', $shift_date)->exists()) {
                return redirect()->back()->withErrors(['error' => 'Dit personeel is roostervrij op deze datum!']);
            } else if ($employee_medical_leave->whereDate('end', '>', $shift_date)->exists()) {
                return redirect()->back()->withErrors(['error' => 'Dit personeel is ziek op deze datum!']);
            } else {
                Event::create([
                    'employee_id' => $id,
                    'status_id' => $absence_reason ? 2 : 1,
                    'start' =>  $absence_reason ? $absence_start : $shift_start_formatted,
                    'end' => $absence_reason ? $absence_end : $shift_end_formatted,
                    'sick' => $absence_reason === 'sick',
                ]);
            }
        }

        return redirect('/users/admin')->with('success', 'Aanwezigheidsgegevens zijn succesvol geregistreerd!');;
    }

    public function calendar() {
        return view('calendar');
    }

    // Naar administratiepaneel navigeren
    public function admin() {
        $session_role = Auth::user()->role_id;
        $employees = Employee::all()->sortByDesc('active');

        if ($session_role === 1) {
            return view('admin.admin', ['employees' => $employees]);
        }

        return redirect()->back();
    }

    // Zieke personeel beter melden
    public function reportWell(int $id) {
        $employee_medical_leave = Event::where('employee_id', $id)->whereDate('start', now())->where('sick', true);
        $employee_medical_leave->update(['sick' => false]);

        return redirect()->back();
    }
}
