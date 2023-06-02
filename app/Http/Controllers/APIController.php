<?php

namespace App\Http\Controllers;

use App\Models\Employee;

class APIController extends Controller
{
    /**
     * Checkt personeel in en uit m.b.v. een API-oproep.
     */
    public function apiTogglePresence(string $rfid, string $api_key) {
        if ($api_key === '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ') {
            $employee = Employee::where('rfid', $rfid)->firstOrFail();

            if ($employee) {
                $present = !$employee->present;
                $employee_action = $present ? 'CHECKED OUT' : 'CHECKED IN';

                $employee_last_activity = strtotime($employee->updated_at);
                $employee_session_duration = intval((strtotime(now()) - $employee_last_activity) / 60);

                $new_log_data = [
                    'employee_id' => $employee->id,
                    'presence_state' => $employee_action,
                    'session_time' => $employee_session_duration,
                ];
                $employee->logging()->create($new_log_data);

                $updated_employee_data = [
                    'last_check_in' => $present ? now() : $employee->last_check_in,
                    'last_check_out' => !$present ? now() : $employee->last_check_out,
                    'present' => $present,
                ];
                $employee->update($updated_employee_data);

                return redirect()->back();
            } else {
                return 'Er is geen personeel gelinkt aan het RFID ' . $rfid;
            }
        }
        return 'Het opgegeven API-sleutelnummer is ongeldig';
    }
}
