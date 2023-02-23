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
        $start = $request->start;
        $end = $request->end;

        $events = Event::whereBetween('start', [$start, $end])->get();

        $event_list = [];
        foreach ($events as $event) {
            $event_list[] = [
                'id' => $event->id,
                'title' => $event->title,
                'start' => $event->start,
                'end' => $event->end,
            ];
        }

        $json_data = response()->json($event_list);

        return $json_data;
    }
}
