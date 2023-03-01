<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index() {
        $session_role = Auth::user()->role_id;
        $present_users = User::where('active', true)->where('present', true)->paginate();
        $absent_users = User::where('active', true)->where('present', false)->paginate();

        return view('dashboard', ['present_users' => $present_users, 'absent_users' => $absent_users, 'session_role' => $session_role]);
    }

    public function show(int $id) {
        $user = User::findOrFail($id);
        $present = !$user->present;
        $activity_time = now();
        $updateData = ['present' => $present];

        if ($present) {
            $updateData['last_check_in'] = $activity_time;
        } else {
            $updateData['last_check_out'] = $activity_time;
        }

        $user->update($updateData);

        return redirect()->back();
    }

    public function edit($id) {
        $user = User::findOrFail($id);

        return view('admin.edit', ['user' => $user]);
    }

    public function update(int $id)
    {
        $user = User::findOrFail($id);
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
        $users = User::all()->sortByDesc('active');

        if ($session_role === 1) {
            return view('admin.admin', ['users' => $users]);
        }

        return redirect()->back();
    }
}
