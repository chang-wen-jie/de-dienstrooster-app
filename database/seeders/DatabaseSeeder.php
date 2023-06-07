<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Event;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run()
    {
        Employee::create([
            'name' => 'Administratie',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'rfid' => 'ABCDEFG12345678',
            'account_type' => 'admin',
            'account_status' => 'active',
            'last_check_in' => now(),
            'last_check_out'=> now(),
        ]);

        Employee::factory(30)->create();
        Event::factory(100)->create();
    }
}
