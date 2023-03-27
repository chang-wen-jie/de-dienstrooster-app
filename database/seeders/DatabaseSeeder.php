<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Event;
use App\Models\Role;
use App\Models\Employee;
use App\Models\Status;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        Role::create([
            'role' => 'admin',
        ]);

        Role::create([
            'role' => 'user',
        ]);

        Status::create([
            'status' => 'on_duty',
        ]);

        Status::create([
            'status' => 'on_leave',
        ]);

        Employee::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'role_id' => 1,
            'last_check_in' => now(),
            'last_check_out'=> now(),
            'present' => true,
            'active' => true,
        ]);

        Employee::factory(15)->create();

        Event::factory(100)->create();
    }
}
