<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Diensten inroosteren en afwezigheden registreren.
     */
    public function setEvent(Request $request, int $id)
    {
        $employee = Employee::findOrFail($id);

        $shift_date = $request->input('shift-date');
        $shift_time_start = $request->input('shift-time-start');
        $shift_time_end = $request->input('shift-time-end');

        $shift_start = Carbon::parse($shift_date.' '.$shift_time_start)->format('Y-m-d H:i:s');
        $shift_end = Carbon::parse($shift_date.' '.$shift_time_end)->format('Y-m-d H:i:s');

        $absence_reason = $request->input('absence-reason');
        $absence_start = $request->input('absence-date-start');
        $absence_end = $request->input('absence-date-end');

        $employee_shifts = $employee->event()->where(['event_type' => 'shift'])->get();
        $employee_leaves = $employee->event()->where(['event_type' => 'leave'])->get();

        if ($absence_reason && $employee_shifts) {
            foreach ($employee_shifts as $employee_shift) {
                $affected_employee_scheduled_shift = $employee_shift->whereDate('end', '<=', $absence_end);

                if ($affected_employee_scheduled_shift->exists()) {
                    $affected_employee_scheduled_shift->first()->delete();
                }
            }

            $absence_data = [
                'event_type' => $absence_reason,
                'start' => $absence_start,
                'end' => $absence_end,
                'called_in_sick' => $absence_reason === 'sick',
            ];
            $employee->event()->create($absence_data);

            return redirect()->back()->with(['success' => 'De absentie is succesvol geregistreerd.']);
        } else if ($employee_shifts->first()->whereDate('start', $shift_date)->exists()) {
            $shift_data = [
                'start' => $shift_start,
                'end' => $shift_end,
            ];

            $employee_shifts->whereDate('start', $shift_date)->first()->update($shift_data);

            return redirect()->back()->with(['success' => 'De dienst is succesvol aangepast.']);
        } else if ($employee_leaves->first()->whereDate('end', '>', $shift_date)->exists()) {
            return redirect()->back()->withErrors(['error' => 'Dit personeel is roostervrij op deze datum!']);
        } else {
            $shift_data = [
                'employee_id' => $employee->id,
                'event_type' => $absence_reason ? : 'shift',
                'start' => $absence_reason ? $absence_start : $shift_start,
                'end' => $absence_reason ? $absence_end : $shift_end,
                'called_in_sick' => $absence_reason === 'sick',
            ];

            $employee->event()->create($shift_data);

            return redirect()->back()->with(['success' => 'De dienst is succesvol ingeroosterd.']);
        }
    }

    /**
     * Basisrooster ophalen.
     */
    public function getSchedule(int $id, int $week)
    {
        $employee = Employee::findOrFail($id);
        $schedule = view('components.custom.employee-shift-schedule', ['employee' => $employee, 'week' => $week])->render();

        return ['schedule' => $schedule];
    }

    /**
     * Basisrooster opstellen.
     */
    public function setSchedule(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $weekdays = $request->input('weekdays');
        $week = $request->input('week');

        $schedule_next_year = request()->has('schedule-next-year');

        if ($schedule_next_year && !$weekdays) {
            return redirect()->back()->withErrors(['error' => 'Er is nog geen basisrooster opgesteld voor dit personeel!']);
        } else {
            foreach ($weekdays as $weekday) {
                $employee_scheduled_shift = $employee->schedule()->where(['weekday' => $weekday, 'week' => $week])->first();

                $shift_start_time = $request->input('shift-start-time-' . $weekday);
                $shift_end_time = $request->input('shift-end-time-' . $weekday);

                if ($shift_start_time && $shift_end_time)
                {
                    $schedule_start = $request->input('schedule-start-date');
                    $schedule_end = $request->input('schedule-end-date');

                    $scheduled_day = Carbon::parse($weekday);
                    $scheduling_start = Carbon::parse($schedule_start);
                    $scheduling_end = Carbon::parse($schedule_end);

                    $weeks_remaining = $scheduling_end->diffInWeeks($scheduling_start);

                    for ($i = $week - 1; $i <= $weeks_remaining; $i += 5) {
                        $scheduled_shift_date = $schedule_next_year
                            ? $scheduling_start->copy()->addYear()->addWeeks($i)->next($scheduled_day->format('l'))
                            : $scheduling_start->copy()->addWeeks($i)->addDays($scheduled_day->dayOfWeek - $scheduling_start->dayOfWeek)->startOfDay();

                        if ($scheduled_shift_date->greaterThanOrEqualTo($scheduling_start)) {
                            $scheduled_shift_start = $scheduled_shift_date->copy()->setTimeFromTimeString($shift_start_time)->toDateTime();
                            $scheduled_shift_end = $scheduled_shift_date->copy()->setTimeFromTimeString($shift_end_time)->toDateTime();

                            $event_data = [
                                'employee_id' => $employee->id,
                                'event_type' => 'shift',
                                'start' => $scheduled_shift_start,
                                'end' => $scheduled_shift_end,
                                'called_in_sick' => false,
                            ];

                            $employee_existing_shift = $employee->event()->whereDate('start', $scheduled_shift_start)->first();

                            if ($employee_existing_shift) {
                                $employee_existing_shift->update($event_data);
                            } else {
                                $employee->event()->create($event_data);
                            }
                        }
                    }
                    $scheduled_shift_data = [
                        'employee_id' => $employee->id,
                        'weekday' => $weekday,
                        'week' => $week,
                        'shift_start_time' => $shift_start_time,
                        'shift_end_time' => $shift_end_time,
                    ];

                    if ($employee_scheduled_shift) {
                        $employee_scheduled_shift->update($scheduled_shift_data);
                    } else {
                        $employee->schedule()->create($scheduled_shift_data);
                    }
                } elseif ($employee_scheduled_shift) {
                    $employee_upcoming_shifts = $employee->event()->whereDate('start', '>', now());

                    $employee_upcoming_shifts->delete();
                    $employee_scheduled_shift->delete();
                }
            }
        }
        return redirect()->back()->with(['success' => 'Het basisrooster is succesvol opgesteld.']);
    }
}
