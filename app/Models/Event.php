<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'status_id',
        'start',
        'end',
        'sick'
    ];

    protected $dates = ['start', 'end'];


    public function employee()
    {
        return $this->hasOne(Employee::class,'id', 'employee_id');
    }

}
