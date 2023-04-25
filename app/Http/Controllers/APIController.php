<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Logging;
use App\Models\Rfidtoken;
use Illuminate\Http\Request;

class APIController extends Controller
{
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
        if (!empty($_POST['apikey'])) {
            $apikey = $_POST['apikey'];
        } else {
            $apikey = $_GET['apikey'];
        }

        if (!empty($_POST['rfidtoken']))
        {
            $rfidtoken = $_POST['rfidtoken'];
        } else {
            $rfidtoken = $_GET['rfidtoken'];
        }

        if ($apikey != 'XdWa_Fjj2rx-clGX_3.M3WrcsEO-O2qhQrBYzZkp_dwsJvjY.uJ3EuqqFdD_5K4dpCl-eYwLqE6_fE.PgwIQyl3_7jKybmt-BvolK286m_118.jXRLa') {
            return "Invalid apikey: '" . $apikey . "'\r\n";
        }

        if (!empty($rfidtoken)) {
            $token = Rfidtoken::where('uid', $rfidtoken)->get();

            if ($token) {
                $new = false;
                $name = $token->name;

                if ($token->aa_status === 'IN') {
                    $aa_status = 'OUT';
                } elseif ($token->aa_status === 'OUT') {
                    $aa_status = 'IN';
                } else {
                    $aa_status = 'OUT';
                }

                $aa_old_status = $token->aa_status;
                $aa_new_status = $aa_status;
                $modified_at = strtotime($token->updated_at);
                $now = strtotime('now');
                $duration_minutes = intval( ($now - $modified_at) / 60 );
            } else {
                $new = true;
                $aa_status = 'IN';
            }

            if (!$new) {
                $token->update(['uid' => $rfidtoken,'aa_status' => $aa_status]);
                Logging::create(['uid' => $rfidtoken, 'name' => $name, 'aa_old_status' => $aa_old_status, 'aa_new_status' => $aa_new_status, 'duration_minutes' => $duration_minutes, 'logged_at' => $now]);
            } else {
                $token->update(['uid' => $rfidtoken,'aa_status' => $aa_status]);
                Logging::create(['uid' => $rfidtoken, 'name' => $name, 'aa_old_status' => $aa_old_status, 'aa_new_status' => $aa_new_status, 'duration_minutes' => $duration_minutes, 'logged_at' => $now]);
            }
            return "[aa_status]=" . $aa_status . "\r\n [rfidtoken]=" . $rfidtoken . "\r\n [name]=" . $name . "";
        } else {
            return "[aa_status]=Unauthorized\r\n [rfidtoken]=" . $rfidtoken . "\r\n [name]=Unknown";
        }
    }
}
