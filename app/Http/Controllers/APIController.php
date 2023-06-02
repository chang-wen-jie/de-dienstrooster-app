<?php

namespace App\Http\Controllers;

use App\Models\Employee;

class APIController extends Controller
{
    /**
     * Microcontroller API connectie.
     */
    public function apiTogglePresence(string $rfid_token, string $api_key) {
        if ($api_key !== '123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ') {
            return 'Toegang geweigerd. Opgegeven API-sleutelnummer: ' . $api_key;
        }

        $employee = Employee::where('rfid_token', $rfid_token)->firstOrFail();

        if ($employee) {
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

            return '[aa_status]=' . $presence_state . PHP_EOL . '[rfidtoken]=' .$rfid_token . PHP_EOL . '[name]     =' . $employee->name;
        } else {
            return 'Er is geen personeel dat verbonden is met het RFID ' . $rfid_token;
        }

    }
}
