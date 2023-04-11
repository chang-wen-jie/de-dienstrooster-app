<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Event;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Personeel ophalen.
     */
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
     * Personeelsgegevens tonen.
     */
    public function edit(int $id)
    {
        $employee = Employee::findOrFail($id);

        return view('admin.edit', ['employee' => $employee]);
    }

    /**
     * Personeelsgegevens aanpassen.
     */
    public function update(Request $request, int $id)
    {
        $employee = Employee::findOrFail($id);

        $name = $request->input('name');
        $account_status = $request->input('active');
        $employee->update(['name' => $name, 'active' => $account_status]);

        return redirect('/admin')->with('success', 'De personeelsgegevens zijn succesvol aangepast.');
    }

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

        $employee_shifts = $employee->event()->where(['status_id' => 1])->get();
        $employee_leaves = $employee->event()->where(['status_id'=> 2])->get();

        if ($absence_reason && $employee_shifts) {
            foreach ($employee_shifts as $employee_shift) {
                $affected_employee_scheduled_shift = $employee_shift->whereDate('end', '<=', $absence_end);

                if ($affected_employee_scheduled_shift->exists()) {
                    $affected_employee_scheduled_shift->first()->delete();
                }
            }

            $absence_data = [
                'status_id' => $absence_reason === 'leave' ? 2 : 1,
                'start' => $absence_start,
                'end' => $absence_end,
                'sick' => $absence_reason === 'sick',
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
                'status_id' => $absence_reason ? 2 : 1,
                'start' => $absence_reason ? $absence_start : $shift_start,
                'end' => $absence_reason ? $absence_end : $shift_end,
                'sick' => $absence_reason === 'sick',
            ];

            $employee->event()->create($shift_data);

            return redirect()->back()->with(['success' => 'De dienst is succesvol ingeroosterd.']);
        }
    }

    /**
     * Basisrooster opstellen.
     */
    public function setSchedule(Request $request, $id)
    {
        $employee = Employee::findOrFail($id);

        $week_days = $request->input('week-days');
        $week_type = $request->input('week-type');

        foreach ($week_days as $week_day) {
            $shift_start_time = $request->input('shift-start-time-' . $week_day);
            $shift_end_time = $request->input('shift-end-time-' . $week_day);
            $schedule_start_date = $request->input('schedule-start-date');
            $schedule_end_date = $request->input('schedule-end-date');

            $employee_scheduled_shift = $employee->schedule()->where(['week_day' => $week_day, 'type_of_week' => $week_type])->first();

            if ($shift_start_time && $shift_end_time)
            {
                $scheduled_shift_data = [
                    'employee_id' => $employee->id,
                    'day_of_week' => $week_day,
                    'type_of_week' => $week_type,
                    'shift_time_start' => $shift_start_time,
                    'shift_time_end' => $shift_end_time,
                ];

                if ($employee_scheduled_shift) {
                    $employee_scheduled_shift->update($scheduled_shift_data);
                } else {
                    $employee->schedule()->create($scheduled_shift_data);
                }

                $scheduling_start_date = Carbon::parse($schedule_start_date);
                $scheduling_end_date = Carbon::parse($schedule_end_date);
                $scheduled_day = Carbon::parse($week_day);

                $weeks_between = $week_type === 'odd' ? 1 : 2;
                $weeks_remaining = $scheduling_end_date->diffInWeeks($scheduling_start_date);

                for ($i = $weeks_between; $i <= $weeks_remaining; $i += 2) {
                    $scheduled_shift_date = $scheduling_start_date->copy()->addWeeks($i)->startOfDay()->addDays($scheduled_day->dayOfWeek - $scheduling_start_date->dayOfWeek);
                    $scheduled_shift_start = $scheduled_shift_date->copy()->setTimeFromTimeString($shift_start_time)->toDateTime();
                    $scheduled_shift_end = $scheduled_shift_date->copy()->setTimeFromTimeString($shift_end_time)->toDateTime();

                    $event_data = [
                        'employee_id' => $employee->id,
                        'status_id' => 1,
                        'start' => $scheduled_shift_start,
                        'end' => $scheduled_shift_end,
                        'sick' => false,
                    ];

                    $employee_existing_shift = $employee->event()->whereDate('start', $scheduled_shift_start)->first();

                    if ($employee_existing_shift) {
                        $employee_existing_shift->update($event_data);
                    } else {
                        $employee->event()->create($event_data);
                    }
                }
            } elseif ($employee_scheduled_shift) {
                // ANDERSOM NU
                $employee_upcoming_shifts = $employee->event()->whereDate('start', '>', now())->whereRaw('WEEK(start, 3) % 2 != 0 AND DAYNAME(start) = ?', [$week_day]);

                $employee_upcoming_shifts->delete();
                $employee_scheduled_shift->delete();
            }
        }

        return redirect()->back()->with(['success' => 'Het basisrooster is succesvol opgesteld.']);
    }
}
