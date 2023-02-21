<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'email',
        'name',
        'password',
        'latest_check_in',
        'latest_check_out',
        'email_verified_at',
        'present',
        'active',
        'remember_token',
    ];

    public $timestamps = false;
}
