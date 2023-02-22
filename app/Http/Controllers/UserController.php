<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index() {
        $session_id = Auth::user()->id;
        $session_user = User::where('id', $session_id)->firstOrFail();
        $session_role = Role::where('id', $session_user->role_id)->firstOrFail();

        $present_users = User::where('active', true)->where('present', true)->paginate();
        $absent_users = User::where('active', true)->where('present', false)->paginate();

        return view('dashboard', ['present_users' => $present_users, 'absent_users' => $absent_users, 'session_role' => $session_role]);
    }

    public function show($id) {
        $user = User::findOrFail($id);
        $presence_status = !$user->present;
        $user->update([
            'present' => $presence_status,
            'last_check_in' => now(),
            'last_check_out' => now(),
        ]);

        return redirect()->back();
    }

    public function edit($id) {
        $user = User::findOrFail($id);

        return view('admin.edit', ['user' => $user]);
    }

    public function update($id)
    {
        $user = User::whereId($id);
        $input = request('name');
        $checkbox = request('active');
        $user->update(['name' => $input, 'active' => $checkbox]);

        return redirect('/users/admin');
    }

    public function admin() {
        $users = User::all()->sortByDesc('active');

        $session_id = Auth::user()->id;
        $session_user = User::where('id', $session_id)->firstOrFail();
        $session_role = Role::where('id', $session_user->role_id)->firstOrFail();

        return view('admin.admin', ['users' => $users]);
    }
}
