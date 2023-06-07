<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Carbon\Carbon;

class APIController extends Controller
{
    /**
     * Checkt personeel in en uit vanuit een API-oproep.
     */
    public function togglePresence(string $rfid, string $api_key) {
        if ($api_key === '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ') {
            $employee = Employee::where('rfid', $rfid)->firstOrFail();

            if ($employee) {
                $employee_last_check_in = Carbon::parse($employee->last_check_in);
                $employee_last_check_out = Carbon::parse($employee->last_check_out);
                $employee_is_present = $employee_last_check_in->greaterThan($employee_last_check_out);

                $employee_presence_state = $employee_is_present ? 'CHECKED OUT' : 'CHECKED IN';

                $employee_last_activity = strtotime($employee->updated_at);
                $employee_session_duration = intval((strtotime(now()) - $employee_last_activity) / 60);

                $new_log_data = [
                    'employee_id' => $employee->id,
                    'presence_state' => $employee_presence_state,
                    'session_time' => $employee_session_duration,
                ];
                $employee->log()->create($new_log_data);

                $updated_employee_data = [
                    'last_check_in' => !$employee_is_present ? now() : $employee->last_check_in,
                    'last_check_out' => $employee_is_present ? now() : $employee->last_check_out,
                ];
                $employee->update($updated_employee_data);

                return redirect()->back();
            } else {
                return 'Er is nog geen personeel gekoppeld aan dit RFID!';
            }
        }
        return 'Het opgegeven API-sleutelnummer is ongeldig!';
    }
}
