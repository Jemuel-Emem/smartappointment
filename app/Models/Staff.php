<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
 protected $fillable = [
        'department_id',
        'name',
        'address',
        'phone_number',
        'speciality',
        'rating',
        'service_type',
        'availability'
    ];

    public function ratings()
{
    return $this->hasMany(Staff_Rating::class);
}

public function getAverageRatingAttribute()
{
    return $this->ratings()->avg('rating') ?? 0;
}

 public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }



}
