<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staff_Rating extends Model
{
    protected $fillable = [
        'staff_id',
        'user_id',
        'rating',
        'comment'
    ];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }


}
