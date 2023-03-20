<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Presence;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        $present_users = Employee::where('active', true)->where('present', true)->paginate();
        $absent_users = Employee::where('active', true)->where('present', false)->paginate();

        return view('dashboard', ['present_users' => $present_users, 'absent_users' => $absent_users]);
    }

    public function show(int $id) {
        $employee = Employee::findOrFail($id);
        $present = !$employee->present;
        $activity_time = now();
        $presence_data = ['present' => $present];

        if ($present) {
            $presence_data['last_check_in'] = $activity_time;
        } else {
            $presence_data['last_check_out'] = $activity_time;
        }

        $employee->update($presence_data);

        return redirect()->back();
    }

    public function edit(int $id) {
        $employee = Employee::findOrFail($id);

        return view('admin.edit', ['user' => $employee]);
    }

    public function update(int $id)
    {
        $employee = Employee::findOrFail($id);
        $input = request('name');
        $checkbox = request('active');
        $employee->update(['name' => $input, 'active' => $checkbox]);

        return redirect('/users/admin');
    }

    public function schedule(int $id, string $occasion) {
        Presence::create([
            'employee_id' => $id,
            'status_id' => 1,
            'start' => request('start'),
            'end' => request('end'),
            'called_in_sick' => false,
        ]);

//        return $occasion;
        return redirect('/users/admin');
    }

    public function calendar() {
        return view('calendar');
    }

    public function admin() {
        $session_role = Auth::user()->role_id;
        $employees = Employee::all()->sortByDesc('active');

        if ($session_role === 1) {
            return view('admin.admin', ['users' => $employees]);
        }

        return redirect()->back();
    }
}
