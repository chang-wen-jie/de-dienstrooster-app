<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $table = 'presence';

    protected $fillable = ['employee_id', 'on_duty', 'start', 'shift_end', 'sick'];

    protected $dates = ['start', 'shift_end'];
}
