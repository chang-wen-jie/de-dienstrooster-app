<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'password',
        'latest-check_in',
        'latest-check_out',
        'present',
        'active',
    ];

    public $timestamps = false;
}
