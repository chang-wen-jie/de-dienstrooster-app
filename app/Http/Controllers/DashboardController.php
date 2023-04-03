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

    /**
     * Personeel in- of uitchecken.
     */
    public function togglePresence(int $id) {
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

    /**
     * Ziekgemelde personeel beter melden.
     */
    public function reportRecovery(int $id) {
        $employee_medical_leave = Event::where('employee_id', $id)->whereDate('start', now())->where('sick', true);
        $employee_medical_leave->update(['sick' => false]);

        return redirect()->back();
    }
}
