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
        'role_id',
        'last_check_in',
        'last_check_out',
        'present',
        'active',
    ];

    public function presence()
    {
        return $this->hasMany(Presence::class, 'employee_id', 'id');
//        return $this->hasOne(Presence::class,'id', 'employee_id');
    }
}
