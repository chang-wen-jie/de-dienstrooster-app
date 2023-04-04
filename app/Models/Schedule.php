<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'type_of_week',
        'day_of_week',
        'shift_start_time',
        'shift_end_time',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
