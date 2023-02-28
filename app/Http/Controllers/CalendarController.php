<?php
namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Event;

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

        $events = Event::whereBetween('start', [$start, $end])->get();

        $event_list = [];
        foreach ($events as $event) {
            $employee_id = $event->user_id;
            $employee = User::findOrFail($employee_id);

            $event_list[] = [
                'id' => $event->id,
                'name' => $employee->name,
                'onDuty' => $event->on_duty,
                'start' => $event->start,
                'shiftEnd' => $event->shift_end,
                'sick' => $event->sick,
            ];
        }

        return response()->json($event_list);
    }
}
