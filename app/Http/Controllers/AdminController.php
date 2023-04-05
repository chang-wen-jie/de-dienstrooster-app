<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::user()->role_id === 1)
        {
            $employees = Employee::all()->sortByDesc('active');

            return view('admin.admin', ['employees' => $employees]);
        }

        return redirect()->back();
    }

    /**
     * Personeelsgegevens ophalen.
     */
    public function edit(int $id)
    {
        $employee = Employee::findOrFail($id);

        return view('admin.edit', ['employee' => $employee]);
    }

    /**
     * Personeelsgegevens aanpassen.
     */
    public function updateUser(int $id)
    {
        $employee = Employee::findOrFail($id);
        $input = request('name');
        $checkbox = request('active');
        $employee->update(['name' => $input, 'active' => $checkbox]);

        return redirect('/admin')->with('success', 'De personeelsgegevens zijn succesvol aangepast.');
    }

    /**
     * Diensten inroosteren en afwezigheden registreren.
     */
    public function setEvent(int $id)
    {
        $shift_date = request('shift-date');
        $shift_start = request('shift-start');
        $shift_end = request('shift-end');
        $shift_start_formatted = Carbon::parse($shift_date.' '.$shift_start)->format('Y-m-d H:i:s');
        $shift_end_formatted = Carbon::parse($shift_date.' '.$shift_end)->format('Y-m-d H:i:s');

        $absence_reason = request('absence-reason');
        $absence_start = request('absence-start');
        $absence_end = request('absence-end');

        $employee_shift = Event::where('employee_id', $id)->where('status_id', 1);
        $employee_leave = Event::where('employee_id', $id)->where('status_id', 2);
        $employee_medical_leave = Event::where('employee_id', $id)->where('sick', true);

        if ($absence_reason && $employee_shift->whereDate('start', $absence_start)->exists()) {
            if ($absence_reason === 'leave') {
                return redirect()->back()->withErrors(['error' => 'Dit personeel staat ingeroosterd op deze startdatum!']);
            } else {
                $employee_shift->whereDate('start', $absence_start)->update([
                    'start' => $absence_start,
                    'end' => $absence_end,
                    'sick' => true,
                ]);

                return redirect('/admin')->with(['success' => 'De ziekmelding is succesvol geregistreerd.']);
            }
        } else {
            if ($employee_shift->whereDate('start', $shift_date)->exists()) {
                $employee_shift->whereDate('start', $shift_date)->update([
                    'start' => $shift_start_formatted,
                    'end' => $shift_end_formatted,
                ]);

                return redirect('/admin')->with(['success' => 'De ingeroosterde dienst is succesvol aangepast.']);
            } else if ($employee_leave->whereDate('end', '>', $shift_date)->exists()) {
                return redirect()->back()->withErrors(['error' => 'Dit personeel is roostervrij op deze datum!']);
            } else if ($employee_medical_leave->whereDate('end', '>', $shift_date)->exists()) {
                return redirect()->back()->withErrors(['error' => 'Dit personeel staat ziekgemeld op deze datum!']);
            } else {
                Event::create([
                    'employee_id' => $id,
                    'status_id' => $absence_reason ? 2 : 1,
                    'start' =>  $absence_reason ? $absence_start : $shift_start_formatted,
                    'end' => $absence_reason ? $absence_end : $shift_end_formatted,
                    'sick' => $absence_reason === 'sick',
                ]);

                return redirect('/admin')->with(['success' => 'De dienst is succesvol ingeroosterd.']);
            }
        }
    }

    public function setSchedule(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $isEvenWeek = $request->input('type_of_week') === 'even';

        foreach ($request->input('day_of_week') as $day) {
            $existingSchedule = $employee->schedule()->where('day_of_week', $day)->first();

            $shiftStart = $request->input('shift-start-' . $day);
            $shiftEnd = $request->input('shift-end-' . $day);

            if ($shiftStart && $shiftEnd) {
                $scheduleData = [
                    'employee_id' => $employee->id,
                    'day_of_week' => $day,
                    'type_of_week' => $request->input('type_of_week'),
                    'shift_time_start' => $shiftStart,
                    'shift_time_end' => $shiftEnd,
                ];

                if ($existingSchedule) {
                    $existingSchedule->update($scheduleData);
                } else {
                    $employee->schedule()->create($scheduleData);
                }

                if ($isEvenWeek && !$existingSchedule) {
                    $now = Carbon::now();
                    $endOfYear = Carbon::create($now->year, 12, 31);

                    $weeksUntilEndOfYear = $endOfYear->diffInWeeks($now);

                    // DAG ALTIJD MAANDAG?
                    for ($i = 2; $i <= $weeksUntilEndOfYear; $i += 2) {
                        $startOfWeek = $now->copy()->addWeeks($i)->startOfWeek();
                        $start = $startOfWeek->copy()->setTimeFromTimeString($shiftStart);
                        $end = $startOfWeek->copy()->setTimeFromTimeString($shiftEnd);

                        $eventData = [
                            'employee_id' => $employee->id,
                            'status_id' => 1,
                            'start' => $start,
                            'end' => $end,
                            'sick' => false,
                        ];

                        Event::create($eventData);
                    }
                }
            } elseif ($existingSchedule) {
                $existingSchedule->delete();
            }
        }

        return redirect()->back()->with('success', 'Schedule set successfully!');
    }
}
