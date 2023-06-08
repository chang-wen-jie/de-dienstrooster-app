<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'event_type',
        'event_start',
        'event_end',
        'called_in_sick'
    ];

    protected $dates = ['event_start', 'event_end'];

    public function employee()
    {
        return $this->hasOne(Employee::class,'id', 'employee_id');
    }
}
