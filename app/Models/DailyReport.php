<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    protected $dates = ['reporting_time'];
    protected $fillable = [
        'user_id', 
        'reporting_time', 
        'title', 
        'content'
    ];

}
