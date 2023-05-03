<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Employee extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'last_check_in',
        'last_check_out',
        'present',
        'active',
    ];

    public function event()
    {
        return $this->hasMany(Event::class, 'employee_id', 'id');
    }

    public function schedule()
    {
        return $this->hasMany(Schedule::class, 'employee_id', 'id');
    }

    public function logging()
    {
        return $this->hasMany(Logging::class, 'employee_id', 'id');
    }
}
