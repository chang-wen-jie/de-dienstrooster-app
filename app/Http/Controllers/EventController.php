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
        $shift_start_time = $request->input('shift-start-time');
        $shift_end_time = $request->input('shift-end-time');

        $shift_start = Carbon::parse($shift_date.' '.$shift_start_time)->format('Y-m-d H:i:s');
        $shift_end = Carbon::parse($shift_date.' '.$shift_end_time)->format('Y-m-d H:i:s');

        $absence_reason = $request->input('absence-reason');
        $absence_start = $request->input('absence-date-start');
        $absence_end = $request->input('absence-date-end');

        $employee_shifts = $employee->event()->where(['event_type' => 'shift'])->get();
        $employee_leaves = $employee->event()->where(['event_type' => 'leave'])->get();

        if ($absence_reason && $employee_shifts) {
            foreach ($employee_shifts as $employee_shift) {
                $affected_employee_scheduled_shift = $employee_shift->whereDate('event_end', '<=', $absence_end);

                if ($affected_employee_scheduled_shift->exists()) {
                    $affected_employee_scheduled_shift->first()->delete();
                }
            }

            $absence_data = [
                'event_type' => $absence_reason,
                'event_start' => $absence_start,
                'event_end' => $absence_end,
                'called_in_sick' => $absence_reason === 'sick',
            ];
            $employee->event()->create($absence_data);

            return redirect()->back()->with(['success' => 'De absentie is succesvol geregistreerd.']);
        } else if ($employee_shifts->first()->whereDate('event_start', $shift_date)->exists()) {
            $shift_data = [
                'event_start' => $shift_start,
                'event_end' => $shift_end,
            ];
            $employee_shifts->whereDate('event_start', $shift_date)->first()->update($shift_data);

            return redirect()->back()->with(['success' => 'De dienst is succesvol aangepast.']);
        } else if ($employee_leaves->first()->whereDate('event_end', '>', $shift_date)->exists()) {
            return redirect()->back()->withErrors(['error' => 'Dit personeel is roostervrij op deze datum!']);
        } else {
            $shift_data = [
                'employee_id' => $employee->id,
                'event_type' => $absence_reason ? : 'shift',
                'event_start' => $absence_reason ? $absence_start : $shift_start,
                'event_end' => $absence_reason ? $absence_end : $shift_end,
                'called_in_sick' => $absence_reason === 'sick',
            ];

            $employee->event()->create($shift_data);

            return redirect()->back()->with(['success' => 'De dienst is succesvol ingeroosterd.']);
        }
    }

    /**
     * Basisrooster ophalen.
     */
    public function getSchedule(int $id, int $scheduled_week)
    {
        $employee = Employee::findOrFail($id);
        $schedule = view('components.custom.employee-shift-schedule', ['employee' => $employee, 'scheduled_week' => $scheduled_week])->render();

        return ['schedule' => $schedule];
    }

    /**
     * Basisrooster opstellen.
     */
    public function setSchedule(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $scheduled_week = $request->input('scheduled-week');
        $days_of_week = $request->input('days-of-week');

        $schedule_next_year = request()->has('schedule-next-year');

        if ($schedule_next_year && !$days_of_week) {
            return redirect()->back()->withErrors(['error' => 'Er is nog geen basisrooster opgesteld voor dit personeel!']);
        } else {
            foreach ($days_of_week as $day_of_week) {
                $employee_scheduled_shift = $employee->schedule()->where([ 'scheduled_week' => $scheduled_week, 'day_of_week' => $day_of_week])->first();

                $shift_start = $request->input('shift-start-' . $day_of_week);
                $shift_end = $request->input('shift-end-' . $day_of_week);

                if ($shift_start && $shift_end)
                {
                    $schedule_start = $request->input('schedule-start-date');
                    $schedule_end = $request->input('schedule-end-date');

                    $scheduled_day = Carbon::parse($day_of_week);
                    $scheduling_start = Carbon::parse($schedule_start);
                    $scheduling_end = Carbon::parse($schedule_end);

                    $weeks_remaining = $scheduling_end->diffInWeeks($scheduling_start);

                    for ($i = $scheduled_week - 1; $i <= $weeks_remaining; $i += 5) {
                        $scheduled_shift_date = $schedule_next_year
                            ? $scheduling_start->copy()->addYear()->addWeeks($i)->next($scheduled_day->format('l'))
                            : $scheduling_start->copy()->addWeeks($i)->addDays($scheduled_day->dayOfWeek - $scheduling_start->dayOfWeek)->startOfDay();

                        if ($scheduled_shift_date->greaterThanOrEqualTo($scheduling_start)) {
                            $scheduled_shift_start = $scheduled_shift_date->copy()->setTimeFromTimeString($shift_start)->toDateTime();
                            $scheduled_shift_end = $scheduled_shift_date->copy()->setTimeFromTimeString($shift_end)->toDateTime();

                            $event_data = [
                                'employee_id' => $employee->id,
                                'event_type' => 'shift',
                                'event_start' => $scheduled_shift_start,
                                'event_end' => $scheduled_shift_end,
                                'called_in_sick' => false,
                            ];

                            $employee_existing_shift = $employee->event()->whereDate('event_start', $scheduled_shift_start)->first();

                            if ($employee_existing_shift) {
                                $employee_existing_shift->update($event_data);
                            } else {
                                $employee->event()->create($event_data);
                            }
                        }
                    }
                    $scheduled_shift_data = [
                        'employee_id' => $employee->id,
                        'scheduled_week' => $scheduled_week,
                        'day_of_week' => $day_of_week,
                        'shift_start' => $shift_start,
                        'shift_end' => $shift_end,
                    ];

                    if ($employee_scheduled_shift) {
                        $employee_scheduled_shift->update($scheduled_shift_data);
                    } else {
                        $employee->schedule()->create($scheduled_shift_data);
                    }
                } elseif ($employee_scheduled_shift) {
                    $employee_upcoming_shifts = $employee->event()->whereDate('event_start', '>', now());

                    $employee_upcoming_shifts->delete();
                    $employee_scheduled_shift->delete();
                }
            }
        }
        return redirect()->back()->with(['success' => 'Het basisrooster is succesvol opgesteld.']);
    }
}
