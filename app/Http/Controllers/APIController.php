<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class APIController extends Controller
{
    /**
     * Personeel in- of uitchecken.
     */
    public function togglePresence(int $id) {
        if ( !empty($_POST['apikey']) ) $apikey = $_POST['apikey']; else $apikey = $_GET['apikey'];

        if ( !empty($_POST['rfidtoken']) ) $rfidtoken = $_POST['rfidtoken']; else $rfidtoken = $_GET['rfidtoken'];

        if ( $apikey != 'XdWa_Fjj2rx-clGX_3.M3WrcsEO-O2qhQrBYzZkp_dwsJvjY.uJ3EuqqFdD_5K4dpCl-eYwLqE6_fE.PgwIQyl3_7jKybmt-BvolK286m_118.jXRLa' ) {
            echo "RESULT:ERROR\r\n";
            echo "  Invalid apikey: '" . $apikey . "'\r\n";
            exit;
        }

        // Make MySQLi throw exceptions
        mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

        // Connect to database and initialize the database
        try {

            // Try Connect to the DB with mysqli_connect function - Params {hostname, userid, password, dbname}
            $mysqli = mysqli_connect("localhost", "maas_api_pusr", "6K3RULV-HaubBRSjI.AY9yz7P0*OVMFM", "maas_api_pro");

            // Create table rfidtoken
            $statement = $mysqli->prepare("CREATE TABLE IF NOT EXISTS rfidtoken (uid VARCHAR(24) NOT NULL, aa_status VARCHAR(5), name VARCHAR(50), modified_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (uid), INDEX USING BTREE (aa_status))");
            $statement->execute(); // Execute the statement.

            // Create table logging
            $statement = $mysqli->prepare("CREATE TABLE IF NOT EXISTS logging (log_id INT NOT NULL AUTO_INCREMENT, uid VARCHAR(24), name VARCHAR(50), aa_old_status VARCHAR(5), aa_new_status VARCHAR(5), duration_minutes INT, logged_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY (log_id), INDEX USING BTREE (uid,aa_new_status,logged_at), INDEX USING BTREE (logged_at,aa_new_status,uid))");
            $statement->execute(); // Execute the statement.
        }

        catch (mysqli_sql_exception $e) {
            // Failed to connect? Lets see the exception details..
            echo "RESULT:ERROR\r\n";
            echo "  MySQLi Error Code: " . $e->getCode() . "\r\n";
            echo "  Exception Msg: " . $e->getMessage();
            exit;
        }

        // Check $rfidtoken
        if ( !empty($rfidtoken) ) {
            // 202 Accepted
            http_response_code(202);
            // Query the database
            try {
                // Select data for rfidtoken
                $statement = $mysqli->prepare("SELECT aa_status, name, modified_at FROM rfidtoken WHERE uid = ?");
                $statement->bind_param("s", $rfidtoken);
                $statement->execute(); // Execute the statement.
                $result = $statement->get_result();

                if ($result->num_rows == 1) {
                    $new = false;
                    $row = $result->fetch_assoc();

                    // Get person's name
                    if ( !empty($row['name']) ) {
                        $name = $row['name'];
                    } else {
                        $name = 'Onbekend';
                    }

                    // Switch IN <> OUT
                    if ($row['aa_status'] == 'IN') {
                        $aa_status = 'OUT';
                    } elseif ($row['aa_status'] == 'OUT') {
                        $aa_status ='IN';
                    } else {
                        $aa_status = 'OUT';
                    }

                    $aa_old_status = $row['aa_status'];
                    $aa_new_status = $aa_status;
                    $modified_at = strtotime($row['modified_at']);
                    $now = strtotime('now');
                    $duration_minutes = intval( ($now - $modified_at) / 60 );
                } else {
                    $new = true;
                    $aa_status = 'IN';
                }

                if (!$new) {

                    // Update rfidtoken status
                    $statement = $mysqli->prepare("UPDATE rfidtoken SET aa_status=?, modified_at=NOW() WHERE uid=?");
                    $statement->bind_param("ss", $aa_status, $rfidtoken);
                    $statement->execute();

                    // Insert logging
                    $statement = $mysqli->prepare("INSERT INTO logging (uid, name, aa_old_status, aa_new_status, duration_minutes, logged_at) VALUES (?, ?, ?, ?, ?, NOW())");
                    $statement->bind_param("ssssi", $rfidtoken, $name, $aa_old_status, $aa_new_status, $duration_minutes);
                    $statement->execute();
                } else {

                    $statement = $mysqli->prepare("INSERT INTO rfidtoken (uid, aa_status, modified_at) VALUES (?, ?, NOW())");
                    $statement->bind_param("ss", $rfidtoken, $aa_status);
                    $statement->execute();

                    // Insert logging
                    $statement = $mysqli->prepare("INSERT INTO logging (uid, name, aa_old_status, aa_new_status, duration_minutes, logged_at) VALUES (?, ?, ?, ?, ?, NOW())");
                    $statement->bind_param("ssssi", $rfidtoken, $name, $aa_old_status, $aa_new_status, $duration_minutes);
                    $statement->execute();
                }
            } catch (mysqli_sql_exception $e) {
                // Failed to connect? Lets see the exception details..
                echo "RESULT:ERROR\r\n";
                echo "  MySQLi Error Code: " . $e->getCode() . "\r\n";
                echo "  Exception Msg: " . $e->getMessage();
                exit;
            }

            // Return the result
            echo "[aa_status]=" . $aa_status . "\r\n";
            echo "[rfidtoken]=" . $rfidtoken . "\r\n";
            echo "[name]     =" . $name      . "\r\n";
            exit;
        } else {
            // 401 Unauthorized (RFC 7235)
            http_response_code(401);

            echo "[aa_status]=Unauthorized\r\n";
            echo "[rfidtoken]=" . $rfidtoken . "\r\n";
            echo "[name]     =Unknown\r\n";
            exit;
        }

        // Close MySQL connection
        mysqli_close($mysqli);

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
}
