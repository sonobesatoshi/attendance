<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    public function rests()
        {
            return $this->hasMany(Rest::class);
        }

    public function user()
        {
            return $this->belongsTo(User::class);
        }

    protected $fillable = [
        'user_id',
        'date',
        'start_time',
        'end_time',
        'total'
    ];


}

