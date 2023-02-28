<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'on_duty', 'start', 'shift_end', 'sick'];

    protected $dates = ['start', 'shift_end'];
}
