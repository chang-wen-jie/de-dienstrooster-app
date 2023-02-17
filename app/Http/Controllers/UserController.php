<?php

namespace App\Http\Controllers;

use App\Models\User;

class UserController extends Controller
{
    public function index() {
        $present_users = User::where('present', true)->paginate(10);
        $absent_users = User::where('present', false)->paginate(10);

        return view('welcome', ['present_users' => $present_users], ['absent_users' => $absent_users]);
    }

    public function create() {
    }

    public function store() {

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

    public function edit() {

    }

    public function update($id) {
    }

    public function destroy() {

    }
}
