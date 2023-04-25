<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
    use HasFactory;

    protected $fillable = [
        'uid',
        'name',
        'aa_old_status',
        'aa_new_status',
        'duration_minutes',
        'logged_at',
    ];

    protected $dates = ['logged_at'];
}
