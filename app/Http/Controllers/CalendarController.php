<?php
namespace App\Http\Controllers;
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
            $event_list[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start,
                'employed' => $event->employed,
                'in_office' => $event->in_office,
            ];
        }

        return response()->json($event_list);
    }
}
