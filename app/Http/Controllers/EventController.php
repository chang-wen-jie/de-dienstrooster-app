<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Event;

class EventController extends Controller
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

        return response()->json($event_list);
    }
}

//
//namespace App\Http\Controllers;
//
//use Illuminate\Http\Request;
//use App\Models\Event;
//
//class EventController extends Controller
//{
//    public function index(Request $request)
//    {
//        if ($request->ajax()) {
//            $data = Event::whereDate('event_start', '>=', $request->start)
//                ->whereDate('event_end', '<=', $request->end)
//                ->get(['id', 'event_name', 'event_start', 'event_end']);
//
//            return response()->json($data);
//        }
//        $data = Event::all();
//        $json = response()->json($data);
//
//        return view('calendar', ['json' => $json]);
//    }
//
//    public function calendarEvents(Request $request)
//    {
//
//        switch ($request->type) {
//            case 'create':
//                $event = Event::create([
//                    'event_name' => $request->event_name,
//                    'event_start' => $request->event_start,
//                    'event_end' => $request->event_end,
//                ]);
//
//                return response()->json($event);
//                break;
//
//            case 'edit':
//                $event = Event::find($request->id)->update([
//                    'event_name' => $request->event_name,
//                    'event_start' => $request->event_start,
//                    'event_end' => $request->event_end,
//                ]);
//
//                return response()->json($event);
//                break;
//
//            case 'delete':
//                $event = Event::find($request->id)->delete();
//
//                return response()->json($event);
//                break;
//
//            default:
//                # ...
//                break;
//        }
//    }
//}
