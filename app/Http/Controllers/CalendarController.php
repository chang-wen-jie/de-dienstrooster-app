<?php
namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Presence;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar');
    }

    public function events(Request $request)
    {
        /**
         * Haalt de huidige start- en einddatum van de geselecteerde datumbereik op.
         */
        $start = $request->get('start');
        $end = $request->get('end');

        $events = Presence::whereBetween('start', [$start, $end])->get();

        $event_list = [];
        foreach ($events as $event) {
            $employee_id = $event->employee_id;
            $employee = Employee::findOrFail($employee_id);

            $event_list[] = [
                'name' => $employee->name,
                'id' => $event->id,
                'status' => $event->status_id,
                'start' => $event->start,
                'end' => $event->end,
                'sick' => $event->called_in_sick,
            ];
        }

        return response()->json($event_list);
    }
}
