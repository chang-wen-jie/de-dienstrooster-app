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

            $last_activity = strtotime($employee->updated_at);
            $activity_datetime = strtotime('now');
            $activity_duration = intval( ($activity_datetime - $last_activity) / 60 );
        } else {
            return 'Er is geen personeel dat overeenkomt met een personeelsnummer ' . $employee_id;
        }

        $logging_data = [
            'employee_id' => $id,
            'presence_state' => $presence_state,
            'activity_duration_minutes' => $activity_duration,
        ];
        $employee->logging()->create($logging_data);
    }
}
