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

    // public function getByUserId($id)
    // {
    //     return $this->where('user_id', $id)->orderBy('reporting_time', 'desc')->get();
    // }

    // public function fetchSearchingReport($id, $month)
    // {
    //     return $this->where('user_id', $id)
    //         ->where('reporting_time', 'LIKE', '%' . $month . '%')
    //         ->orderBy('reporting_time', 'desc')
    //         ->get();
    // }

    public function getReportList($user_id, $inputs) {
        $builder = $this->newQuery();
        if (!empty($inputs['search_month'])) {
          $builder->searchTitle($inputs['search_month']);
        }
        $builder->where('user_id', $user_id);
        $builder->orderBy('reporting_time', 'desc');
        
        return $builder->get();
      }
      
      public function scopeSearchTitle($query, $searchMonth) 
      {
        return $query->where('reporting_time', 'LIKE', '%' . $searchMonth . '%');
      }
}
