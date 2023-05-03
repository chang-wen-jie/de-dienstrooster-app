<?php

namespace App\Http\Controllers;

use App\Models\Employee;

class APIController extends Controller
{
    /**
     * Microcontroller API connectie.
     */
    public function connectAPI(int $id) {
        return $id;

        $employee_id = $id;

        if ($api_key != 'XdWa_Fjj2rx-clGX_3.M3WrcsEO-O2qhQrBYzZkp_dwsJvjY.uJ3EuqqFdD_5K4dpCl-eYwLqE6_fE.PgwIQyl3_7jKybmt-BvolK286m_118.jXRLa') {
            return 'Ongeldige API sleutelnummer: ' . $api_key;
        }

        $employee = Employee::findOrFail('employee_id', $rfid_token);

        if ($employee) {
            $present = !$employee->present;
            $presence_state = $employee->present ? 'CHECKED IN' : 'CHECKED OUT';

            $employee->update(['present' => $present]);

            $previous_activity_time = strtotime($employee->updated_at);
            $current_activity_time = strtotime('now');
            $session_duration_minutes = intval($current_activity_time - $previous_activity_time / 60 );
        } else {
            return 'Er is geen personeel dat overeenkomt met een personeelsnummer ' . $employee_id;
        }

        $logging_data = [
            'employee_id' => $id,
            'presence_state' => $presence_state,
            'session_duration_minutes' => $session_duration_minutes,
        ];
        $employee->logging()->create($logging_data);
    }
}
