<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'absent_reason',
        'revision_request',
    ];

    protected $dates = [
        'date_time',
        'start_time',
        'end_time',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
