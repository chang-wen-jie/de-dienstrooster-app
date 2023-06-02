<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            'rfid_token' => '23404992',
            'name' => 'Gast 5',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'role_id' => 1,
            'last_check_in' => now(),
            'last_check_out'=> now(),
            'present' => false,
            'active' => true,
        ]);

        Employee::create([
            'rfid_token' => '692A6F19',
            'name' => 'Gast 1',
            'email' => 'gast1@example.com',
            'password' => Hash::make('admin'),
            'role_id' => 1,
            'last_check_in' => now(),
            'last_check_out'=> now(),
            'present' => false,
            'active' => true,
        ]);

        Employee::create([
            'rfid_token' => '897A5E19',
            'name' => 'Gast 2',
            'email' => 'gast2@example.com',
            'password' => Hash::make('admin'),
            'role_id' => 1,
            'last_check_in' => now(),
            'last_check_out'=> now(),
            'present' => false,
            'active' => true,
        ]);

        Employee::create([
            'rfid_token' => 'E0C65DFD',
            'name' => 'Gast 3',
            'email' => 'gast3@example.com',
            'password' => Hash::make('admin'),
            'role_id' => 1,
            'last_check_in' => now(),
            'last_check_out'=> now(),
            'present' => false,
            'active' => true,
        ]);

        Employee::create([
            'rfid_token' => 'A36B5CAC',
            'name' => 'Gast 4',
            'email' => 'gast4@example.com',
            'password' => Hash::make('admin'),
            'role_id' => 2,
            'last_check_in' => now(),
            'last_check_out'=> now(),
            'present' => false,
            'active' => true,
        ]);

//        Employee::factory(15)->create();
    }
}
