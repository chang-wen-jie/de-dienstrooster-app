<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Event;

class DashboardController extends Controller
{
    public function index() {
        $active_employees = Employee::where('active', true)->get();

        return view('dashboard', ['present_users' => $active_employees->where('present', true), 'absent_users' => $active_employees->where('present', false)]);
    }

    public function displayKioskMode() {
        $employees = Employee::where('active', true)->get();

        return view('kiosk', ['employees' => $employees]);
    }

    /**
     * Personeel in- of uitchecken.
     */
    public function togglePresence(int $id) {
        $employee = Employee::findOrFail($id);

        $present = !$employee->present;
        $presence_state = $employee->present ? 'OUT' : 'IN';

        $previous_activity_time = strtotime($employee->updated_at);
        $current_activity_time = strtotime('now');
        $session_duration_minutes = intval(($current_activity_time - $previous_activity_time) / 60);

        $logging_data = [
            'employee_id' => $employee->id,
            'presence_state' => $presence_state,
            'session_duration_minutes' => $session_duration_minutes,
        ];
        $employee->logging()->create($logging_data);

        $employee_data = [
            'last_check_in' => $present ? now() : $employee->last_check_in,
            'last_check_out' => !$present ? now() : $employee->last_check_out,
            'present' => $present,
        ];
        $employee->update($employee_data);

        return redirect()->back();
    }

    public function showLogs(int $id) {
        $employee = Employee::findOrFail($id);

        return view('admin.logs', ['employee' => $employee]);
    }

    /**
     * Ziekgemelde personeel beter melden.
     */
    public function reportRecovery(int $id) {
        $employee_medical_leave = Event::where('employee_id', $id)->whereDate('start', now())->where('sick', true);
        $employee_medical_leave->update(['sick' => false]);

        return redirect()->back();
    }
}
