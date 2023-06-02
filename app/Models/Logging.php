<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logging extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id',
        'presence_state',
        'session_time',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class,'id', 'employee_id');
    }
}
