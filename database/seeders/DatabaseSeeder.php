<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Role;
use App\Models\User;
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
            'title' => 'admin',
        ]);

        Role::create([
            'title' => 'user',
        ]);

        User::create([
            'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'last_check_in' => now(),
            'last_check_out'=> now(),
            'present' => true,
            'active' => true,
        ]);

        User::factory(10)->create();
    }
}
