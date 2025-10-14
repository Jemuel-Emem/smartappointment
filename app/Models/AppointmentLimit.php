<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppointmentLimit extends Model
{
    protected $fillable = ['user_id', 'limit', 'timeslot','limit_date'];

   public function user()
    {
        return $this->belongsTo(User::class);
    }
}
