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

        $days_of_week = $request->input('days-of-week');
        $type_of_week = $request->input('type-of-week');

        foreach ($days_of_week as $day_of_week) {
            $shift_time_start = $request->input('shift-time-start-' . $day_of_week);
            $shift_time_end = $request->input('shift-time-end-' . $day_of_week);

            $employee_scheduled_shift = $employee->schedule()->where(['day_of_week' => $day_of_week, 'type_of_week' => $type_of_week])->first();

            if ($shift_time_start && $shift_time_end) {
                $scheduled_shift_data = [
                    'employee_id' => $employee->id,
                    'day_of_week' => $day_of_week,
                    'type_of_week' => $type_of_week,
                    'shift_time_start' => $shift_time_start,
                    'shift_time_end' => $shift_time_end,
                ];

                if ($employee_scheduled_shift) {
                    $employee_scheduled_shift->update($scheduled_shift_data);
                } else {
                    $employee->schedule()->create($scheduled_shift_data);
                }

                $current_date = Carbon::now();
                $year_end = Carbon::create($current_date->year, 12, 31);
                $scheduled_day = Carbon::parse($day_of_week);

                $weeks_in_between = $type_of_week === 'odd' ? 1 : 2;
                $weeks_remaining = $year_end->diffInWeeks($current_date);

                for ($i = $weeks_in_between; $i <= $weeks_remaining; $i += 2) {
                    $scheduled_shift_date = $current_date->copy()->addWeeks($i)->startOfDay()->addDays($scheduled_day->dayOfWeek - $current_date->dayOfWeek);
                    $scheduled_shift_time_start = $scheduled_shift_date->copy()->setTimeFromTimeString($shift_time_start)->toDateTime();
                    $scheduled_shift_time_end = $scheduled_shift_date->copy()->setTimeFromTimeString($shift_time_end)->toDateTime();

                    $event_data = [
                        'employee_id' => $employee->id,
                        'status_id' => 1,
                        'start' => $scheduled_shift_time_start,
                        'end' => $scheduled_shift_time_end,
                        'sick' => false,
                    ];

                    $employee_existing_shift = $employee->event()->whereDate('start', $scheduled_shift_time_start)->first();

                    if ($employee_existing_shift) {
                        $employee_existing_shift->update($event_data);
                    } else {
                        $employee->event()->create($event_data);
                    }
                }
            } elseif ($employee_scheduled_shift) {
                $employee_scheduled_shift->delete();
            }
        }

        return redirect()->back()->with(['success' => 'Het basisrooster is succesvol opgesteld.']);
    }
}
