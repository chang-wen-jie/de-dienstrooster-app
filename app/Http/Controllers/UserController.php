<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index() {
        $present_users = User::where('present', true)->paginate();
        $absent_users = User::where('present', false)->paginate();

        return view('welcome', ['present_users' => $present_users], ['absent_users' => $absent_users]);
    }

    public function show($id) {
        $user = User::findOrFail($id);
        $presence_status = !$user->present;
        $user->update([
            'present' => $presence_status,
            'latest_check_in' => now(),
            'latest_check_out' => now(),
        ]);

        return redirect()->back();
    }

    public function edit($id) {
        $user = User::findOrFail($id);

        return view('edit', ['user' => $user]);
    }

    public function update($id)
    {
        $user = User::whereId($id);
        $input = request('name');
        $checkbox = request('active');
        $user->update(['name' => $input, 'active' => $checkbox]);

        return redirect('/users');
    }

    public function admin() {
        return redirect('admin');
    }
}
