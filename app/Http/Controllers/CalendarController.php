<?php
namespace App\Http\Controllers;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Models\Event;

class CalendarController extends Controller
{
    public function index()
    {
        return view('calendar');
    }

    /**
     * Haalt alle evenementen op met het geselecteerde datumebreik.
     */
    public function fetchEvents(Request $request)
    {
        $event_start = $request->get('start');
        $event_end = $request->get('end');

        $filtered_events = Event::whereBetween('start', [$event_start, $event_end])->get();

        $events = [];
        foreach ($filtered_events as $filtered_event) {
            $employee_id = $filtered_event->employee_id;
            $employee = Employee::findOrFail($employee_id);

            $events[] = [
                'name' => $employee->name,
                'id' => $filtered_event->id,
                'type' => $filtered_event->event_type,
                'start' => $filtered_event->start,
                'end' => $filtered_event->end,
                'sick' => $filtered_event->called_in_sick,
            ];
        }

        return response()->json($events);
    }
}
