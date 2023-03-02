<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index() {
        $session_role = Auth::user()->role_id;
        $present_users = Employee::where('active', true)->where('present', true)->paginate();
        $absent_users = Employee::where('active', true)->where('present', false)->paginate();

        return view('dashboard', ['present_users' => $present_users, 'absent_users' => $absent_users, 'session_role' => $session_role]);
    }

    public function show(int $id) {
        $user = Employee::findOrFail($id);
        $present = !$user->present;
        $activity_time = now();
        $presence_data = ['present' => $present];

        if ($present) {
            $presence_data['last_check_in'] = $activity_time;
        } else {
            $presence_data['last_check_out'] = $activity_time;
        }

        $user->update($presence_data);

        return redirect()->back();
    }

    public function edit($id) {
        $user = Employee::findOrFail($id);

        return view('admin.edit', ['user' => $user]);
    }

    public function update(int $id)
    {
        $user = Employee::findOrFail($id);
        $input = request('name');
        $checkbox = request('active');
        $user->update(['name' => $input, 'active' => $checkbox]);

        return redirect('/users/admin');
    }

    public function calendar() {
        return view('calendar');
    }

    public function admin() {
        $session_role = Auth::user()->role_id;
        $users = Employee::all()->sortByDesc('active');

        if ($session_role === 1) {
            return view('admin.admin', ['users' => $users]);
        }

        return redirect()->back();
    }
}
