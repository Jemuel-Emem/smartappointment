<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
   protected $fillable = [
    'user_id',
    'department_id',
    'staff_id',
    'purpose_of_appointment',
    'appointment_date',
    'appointment_time',
    'status',
    'rated'
];

public function user()
{
    return $this->belongsTo(User::class);
}

public function staff()
{
    return $this->belongsTo(Staff::class);
}


}
