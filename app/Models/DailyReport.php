<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DailyReport extends Model
{
    use SoftDeletes;
    protected $dates = ['reporting_time'];
    protected $fillable = [
        'user_id', 
        'reporting_time', 
        'title', 
        'content'
    ];

    public function getByUserId($id)
    {
        return $this->where('user_id', $id)->orderBy('reporting_time', 'desc')->get();
    }

    public function SearchMonth($id, $month)
    {
        return $this->where([
            ['user_id', $id],
            ['reporting_time', 'LIKE', '%'.$month.'%']
        ])->orderBy('reporting_time', 'desc')
        ->get();
    }
}
