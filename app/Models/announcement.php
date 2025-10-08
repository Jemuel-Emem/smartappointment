<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class announcement extends Model
{
   protected $fillable = [
        'title',
        'message',
        'audience',
        'department_id',
    ];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
