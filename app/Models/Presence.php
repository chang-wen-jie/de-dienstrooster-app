<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presence extends Model
{
    use HasFactory;

    protected $table = 'presence';

    protected $fillable = ['employee_id', 'status_id', 'start', 'end', 'called_in_sick'];

    protected $dates = ['start', 'end'];


    public function employee()
    {
        return $this->hasOne(Employee::class,'id', 'employee_id');
    }

}
