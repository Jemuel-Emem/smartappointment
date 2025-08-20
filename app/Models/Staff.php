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
        'service_type'
    ];

    public function ratings()
{
    return $this->hasMany(Staff_Rating::class);
}

public function getAverageRatingAttribute()
{
    return $this->ratings()->avg('rating') ?? 0;
}

}
